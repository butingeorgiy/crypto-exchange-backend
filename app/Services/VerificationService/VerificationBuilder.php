<?php

namespace App\Services\VerificationService;

use App\Exceptions\ModelExceptions\VerificationRequest\FailedToCreateException as FailedToCreateRequestException;
use App\Models\FileAttachment;
use App\Models\User;
use App\Models\VerificationRequest;
use App\Services\VerificationService\Exceptions\FailedToParseToAttachmentDtoException;
use App\Services\VerificationService\Exceptions\UnableCreateRequestException;
use App\Services\VerificationService\Traits\VerificationBuilderCanParseRequest as CanParseRequest;
use App\Support\Dto\Verification\AttachmentDto;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class VerificationBuilder
{
    use CanParseRequest;

    /**
     * User's first name.
     *
     * @var string
     */
    protected string $firstName;

    /**
     * User's last name.
     *
     * @var string
     */
    protected string $lastName;

    /**
     * User's middle name.
     *
     * @var string|null
     */
    protected ?string $middleName;

    /**
     * User's phone number.
     *
     * @var string
     */
    protected string $phoneNumber;

    /**
     * User's telegram login.
     *
     * @var string
     */
    protected string $telegramLogin;

    /**
     * Request's attachments.
     *
     * @var array<AttachmentDto>
     */
    protected array $attachments;

    /**
     * File attachments models.
     *
     * @var array<FileAttachment>
     */
    protected array $preparedForStoringAttachments;

    /**
     * User model instance.
     *
     * @var User
     */
    protected User $user;

    /**
     * Set user's first name.
     *
     * @param string $firstName
     *
     * @return $this
     */
    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Set user's last name.
     *
     * @param string $lastName
     *
     * @return $this
     */
    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Set user's middle name.
     *
     * @param string $middleName
     *
     * @return $this
     */
    public function setMiddleName(string $middleName): static
    {
        $this->middleName = $middleName;

        return $this;
    }

    /**
     * Set user's phone number.
     *
     * @param string $phoneNumber
     *
     * @return $this
     */
    public function setPhoneNumber(string $phoneNumber): static
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * Set user's telegram login.
     *
     * @param string $login
     *
     * @return $this
     */
    public function setTelegramLogin(string $login): static
    {
        $this->telegramLogin = $login;

        return $this;
    }

    /**
     * Set request's attachments.
     *
     * @param array $attachments
     *
     * @return $this
     *
     * @throws FailedToParseToAttachmentDtoException
     */
    public function setAttachments(array $attachments): static
    {
        try {
            if (count($attachments) === 0) {
                return $this;
            }

            $this->attachments = array_map(function ($item) {
                return new AttachmentDto($item['title'], $item['file']);
            }, $attachments);

            return $this;
        } catch (Throwable $e) {
            Log::error('Failed to parse to Attachment DTO object.', [
                'exception_message' => $e->getMessage()
            ]);

            throw new FailedToParseToAttachmentDtoException;
        }
    }

    /**
     * Set request's user instance.
     *
     * @param User|int $user
     *
     * @return $this
     */
    public function setUser(User|int $user): static
    {
        if (is_numeric($user)) {
            $user = User::findOrFail($user);
        }

        $this->user = $user;

        return $this;
    }

    /**
     * Save request instance to database.
     *
     * @throws Exception
     */
    public function save(): void
    {
        $this->failIfUnableCreateRequest();

        DB::transaction(function () {
            $model = $this->prepareModelInstance();

            $model->save() ?: throw new FailedToCreateRequestException;

            $this->createAndStoreAttachments($model);
        });
    }

    /**
     * Check can user apply verification request.
     * If not, will be thrown exception with cause message.
     *
     * @throws UnableCreateRequestException
     * @throws Exception
     */
    protected function failIfUnableCreateRequest(): void
    {
        if (!isset($this->user)) {
            throw new Exception('User instance did not set.');
        }

        if ($this->user->isVerified()) {
            throw new UnableCreateRequestException('Пользователь уже верифицирован.');
        }

        $hasRequests = $this->user->verificationRequests()->whereIn('status_id', [
            VerificationRequest::$CREATED_STATUS_ID, VerificationRequest::$ACCEPTED_STATUS_ID
        ])->exists();

        if ($hasRequests) {
            throw new UnableCreateRequestException('Пользователь уже отправлял запросы на верификацию.');
        }
    }

    /**
     * Prepare model instance.
     *
     * @return VerificationRequest
     */
    protected function prepareModelInstance(): VerificationRequest
    {
        $instance = new VerificationRequest([
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'phone_number' => $this->phoneNumber,
            'telegram_login' => $this->telegramLogin,
            'status_id' => VerificationRequest::$CREATED_STATUS_ID
        ]);

        if (isset($this->middleName) && !is_null($this->middleName)) {
            $instance->middle_name = $this->middleName;
        }

        $instance->user()->associate($this->user);

        return $instance;
    }

    /**
     * Create and store request's attachments.
     *
     * @param VerificationRequest $model
     */
    protected function createAndStoreAttachments(VerificationRequest $model): void
    {
        foreach ($this->attachments as $item) {
            $file = $item->file;

            while (true) {
                $dir = 'verification_attachments/';
                $fileName = Str::random() . '.' . $file->extension();

                if (FileAttachment::where('file_path', $dir . $fileName)->doesntExist()) {
                    break;
                }
            }

            $model->attachments()->create([
                'title' => $item->title,
                'file_path' => $dir . $fileName
            ]);

            $item->file->storeAs($dir, $fileName);
        }
    }
}