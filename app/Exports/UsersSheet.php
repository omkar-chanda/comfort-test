<?php
namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class UsersSheet implements FromQuery, WithHeadings, WithChunkReading
{
    protected $offset;
    protected $chunkSize;

    public function __construct($offset, $chunkSize)
    {
        $this->offset = $offset;
        $this->chunkSize = $chunkSize;
    }

    public function query()
    {
        return User::query()->offset($this->offset)->limit($this->chunkSize);
    }

    public function headings(): array
    {
        return [
            'ID', 'Name', 'Email', 'Phone', 'Address', 'Street',
            'City', 'State', 'Country', 'Zip', 'Qualification',
            'Remember Token', 'Created At', 'Updated At',
        ];
    }

    public function chunkSize(): int
    {
        return $this->chunkSize;
    }
}
