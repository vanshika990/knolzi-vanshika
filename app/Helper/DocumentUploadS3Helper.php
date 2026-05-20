<?php

namespace App\Helper;

use Illuminate\Support\Facades\Facade;
use Illuminate\Http\Request;
use App\Http\Requests;
use Storage;
use File;

class DocumentUploadS3Helper extends Facade {
    /*
     * For Upload TO S3 All Media
     * * */

    public static function uploadToBucket($imagePath, $s3path) {
        if (!empty($imagePath) && !empty($s3path)) {
            $s3path = 'hint/uploads/images/' . time() . $s3path;
            Storage::disk('s3')->put($s3path, $imagePath);
            return \Storage::disk('s3')->url($s3path);
        } else {
            return redirect()->back()->with('error_message', 'Please select image!!!');
        }
    }

    public static function uploadToBucketNew($path, $files) {
        if (!empty($files)) {
            if (is_array($files)) {
                foreach ($files as $file) {
                    $six_digit_random_number = mt_rand(100000, 999999);
                    $fname = time() . $six_digit_random_number;
                    $s3path = $fname . '.' . $file->getClientOriginalExtension();
                    $imagePath = file_get_contents($file);
                    $s3path = 'assets/' . $path . "/" . time() . $s3path;
                    Storage::disk('s3')->put($s3path, $imagePath);
                    $url[] = \Storage::disk('s3')->url($s3path);
                }
            } else {
                $s3path = time() . '.' . $files->getClientOriginalExtension();
                $imagePath = file_get_contents($files);
                $s3path = 'assets/' . $path . "/" . time() .rand(10,100). $s3path;
                Storage::disk('s3')->put($s3path, $imagePath);
                $url = \Storage::disk('s3')->url($s3path);
            }
            return $url;
        } else {
            return redirect()->back()->with('error_message', 'Please select image!!!');
        }
    }


    public static function uploadToScormBucket($files, $question_id) {
        if (!empty($files)) {
            //if (is_array($files)) {

                //file extension
                /*$extension = $files->getClientOriginalExtension();
        
                //new filename
                $new_filename = time().'.'.$extension;
                
                $s3path = 'scorm_'.$question_id;

                Storage::disk('s3')->makeDirectory($s3path);

                //Upload File
                Storage::disk('s3')->put($s3path.'/'.$new_filename, fopen($files, 'r+'));*/
                $s3path = 'Scorm_Files/question_'.$question_id;
                $dir = public_path()."/uploads/question_".$question_id."/";

                $directories = File::allFiles($dir);
                
                foreach ($directories as $file) {
                    $file_name = str_replace($dir, '', $file);
                    Storage::disk('scorm_zip')->put($s3path.'/'.$file_name, fopen($file, 'r+'));
                }    

                //Do the DB queries to save file URL
                $url = \Storage::disk('scorm_zip')->url($s3path);
            //} 
            return $url.'/story.html';
        }else{
            return redirect()->back()->with('error_message', 'Please select scorm zip file!!!');
        }    
    }

    public static function deleteScormToBucket($filename, $question_id){
        if (!empty($filename)) {
                $s3path = 'Scorm_Files/question_'.$question_id;
                Storage::disk('scorm_zip')->delete($s3path);
            return true;
        }else{
            return redirect()->back()->with('error_message', 'Please select scorm zip file!!!');
        }    
    }

    public static function deleteScormFolder($question_id){
        if (!empty($question_id)) {
            $s3path = 'Scorm_Files/question_'.$question_id;
            Storage::disk('scorm_zip')->deleteDirectory($s3path);
            return true;
        }else{
            return redirect()->back()->with('error_message', 'Please select scorm zip file!!!');
        } 
    }

    /*
     * For Delete TO S3 Media
     * * */

    public static function deleteToBucket($filename) {
        // echo "<pre>"; print_r($filename); die();
        if (!empty($filename)) {
            if (is_array($filename)) {
                foreach ($filename as $file) {
                    $array = explode('/', $file);
                    $name = end($array);
                    $s3path = 'assets/uploads/images/' . $name;
                    Storage::disk('s3')->delete($s3path);
                }
            } else {
                $array = explode('/', $filename);
                $name = end($array);
                $s3path = 'assets/uploads/images/' . $name;
                Storage::disk('s3')->delete($s3path);
            }
            return true;
        } else {
            return redirect()->back()->with('error_message', 'Please select image!!!');
        }
    }

    /*
     * For Demo Purpose function
     * * */

    public function uploadMultiPartFile($file, $folderName, $extension) {
        $path = public_path() . '/uploads/images/' . $folderName;
        $dir = 'uploads/images/' . $folderName;
        if (!is_dir($path)) {
            mkdir($path, 777, true);
        }
        $filename = date('Ymhis') . '.' . $extension;
        $move = $file->move($path, $filename);
        $imagePath = $dir . '/' . $filename;
        return $imagePath;
    }

}
