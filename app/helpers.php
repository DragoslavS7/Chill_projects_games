<?php

if(! function_exists('subDomain')) {
    function subDomain(){
       if(isset($_SERVER['HTTP_HOST'])){
            return explode('.', $_SERVER['HTTP_HOST'])[0];
       }

       return env('UBER_ADMIN_SUB_DOMAIN');
    }
}

if (! function_exists('isUberAdminPortal')) {
    function isUberAdminPortal()
    {
        return env('UBER_ADMIN_SUB_DOMAIN', 'admin') == subDomain();
    }
}

if (! function_exists('saveImage')) {
    function saveImage($image, $subFolderPath, $fileNamePrefix='',$width=null,$height=null){
        $filename = time() . '.' . $image->getClientOriginalExtension();
        $relative_path = str_replace('\\', '/', 'images/' . $subFolderPath . $fileNamePrefix . '_' . $filename);
        $path = str_replace('\\', '/', public_path($relative_path));


        $uploadImage = \Image::make($image->getRealPath());

        if($width) {
            $uploadImage->resize($width, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            if ($uploadImage->height() > $height) {
                $uploadImage->crop($width, $height);
            }
        }

        if (! File::exists(dirname($path))) {
            File::makeDirectory(dirname($path));
        }

        $uploadImage->save($path);
        return '/'.$relative_path;
    }
}
