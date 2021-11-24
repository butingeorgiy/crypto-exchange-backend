<?php

namespace App\Services\VerificationService\Traits;

use App\Http\Requests\Verification\CreateRequest;
use App\Services\AuthenticationService\Client;
use App\Services\VerificationService\Exceptions\FailedToParseToAttachmentDtoException;
use Illuminate\Http\UploadedFile;

trait VerificationBuilderCanParseRequest
{
    /**
     * Parse builder from HTTP request.
     *
     * @param CreateRequest $request
     *
     * @return $this
     *
     * @throws FailedToParseToAttachmentDtoException
     */
    public function parseFromHttpRequest(CreateRequest $request): static
    {
        $this->setFirstName($request->input('first_name'))
            ->setLastName($request->input('last_name'))
            ->setPhoneNumber($request->input('phone_number'))
            ->setTelegramLogin($request->input('telegram_login'));

        if ($request->has('middle_name')) {
            $this->setMiddleName($request->input('middle_name'));
        }

        $this->setUser(
            app(Client::class)->currentUser()
        );

        $this->setAttachments(
            $this->prepareAttachmentsArray(
                $request->file('selfie_attachment'),
                $request->file('scan_attachment')
            )
        );

        return $this;
    }

    /**
     * Prepare attachments to array.
     *
     * @param UploadedFile $selfie
     * @param UploadedFile $scan
     *
     * @return array[]
     */
    protected function prepareAttachmentsArray(UploadedFile $selfie, UploadedFile $scan): array
    {
        return [
            [
                'title' => 'Селфи с паспортом',
                'file' => $selfie
            ],
            [
                'title' => 'Скан / Фото удостоверения личности или паспорта',
                'file' => $scan
            ]
        ];
    }

}