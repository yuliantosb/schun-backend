<?php

namespace App\Helpers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Permission;
use App\Role;

class Common {

    public static function bytesToHuman($bytes)
    {
        $units = ['bytes', 'KB', 'MB', 'GB', 'TB', 'PB'];

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    public static function createSlug($title, $type, $id = 0)
    {
        $slug = Str::slug($title);
        $allSlugs = Common::getRelatedSlugs($slug, $type, $id);
        
        if (! $allSlugs->contains('slug', $slug)){
            return $slug;
        }

        for ($i = 1; $i <= 10; $i++) {
            $newSlug = $slug.'-'.$i;
            if (! $allSlugs->contains('slug', $newSlug)) {
                return $newSlug;
            }
        }

        throw new \Exception('Can not create a unique slug');
    }

    protected static function getRelatedSlugs($slug, $type, $id = 0)
    {
        if ($type == 'permission') {
            return Permission::select('slug')->where('slug', 'like', $slug.'%')
                ->where('_id', '<>', $id)
                ->get();
        }

        if ($type == 'role') {
            return Role::select('slug')->where('slug', 'like', $slug.'%')
                ->where('id', '<>', $id)
                ->get();
        }
    }

    public static function createImageFromBase64($binary, $filename)
    { 

        @list($type, $binary) = explode(';', $binary);
        @list(, $binary) = explode(',', $binary); 
        if($binary!=""){ // storing image in storage/app/public Folder 
            Storage::disk('public')->put($filename,base64_decode($binary)); 
         } 
     }
}