<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Arsip;
use Illuminate\Support\Facades\Storage;

class DeleteOldTrashArsip extends Command
{
    protected $signature = 'arsip:delete-old-trash';
    protected $description = 'Hapus arsip di sampah lebih dari 30 hari';

    public function handle()
    {
        $arsips = Arsip::onlyTrashed()
            ->where('deleted_at', '<=', now()->subDays(30))
            ->with('files')
            ->get();

        foreach ($arsips as $arsip) {

            foreach ($arsip->files as $file) {

                Storage::disk('public')->delete($file->path_file);

                $file->delete();
            }

            $arsip->forceDelete();
        }

        $this->info('Arsip lama berhasil dihapus');
    }
}