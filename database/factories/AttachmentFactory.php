<?php

namespace Database\Factories;

use App\Models\Attachment;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\File;

class AttachmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Attachment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // 테스트를 위해 고아가 된 첨부파일을 만든다.
        // 고아가 된 첨부파일 이란 article_id가 없고 생성된 지 일주일 넘은 테이블 레코드/파일를 의미한다.
        $faker = $this->faker;
        $path = $faker->image(attachments_path());
        $filename = File::basename($path);
        $bytes = File::size($path);
        $mime = File::mimeType($path);

        return [
            'filename' => $filename,
            'bytes' => $bytes,
            'mime' => $mime,
            'created_at' => $faker->dateTimeBetween('-1 months'),
        ];
    }
}
