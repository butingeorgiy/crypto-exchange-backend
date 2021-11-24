<?php

namespace App\Support\Dto\Verification;

use Illuminate\Http\UploadedFile;

class AttachmentDto
{
    public function __construct(
        public string $title,
        public UploadedFile $file
    ) {}
}