<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Aws\S3\S3Client;
use Intervention\Image\ImageManager;

class S3bucketController extends Controller
{

    public function __construct(ImageManager $imageManager)
    {
        $this->middleware('auth');
        $this->imageManager = $imageManager;
    }

    //function that fix the name of the image to be uploaded by removing the special character
    public function fixFileName($fileName)
    {
        $fileName = str_replace("#", "", $fileName);
        $fileName = str_replace("$", "", $fileName);
        $fileName = str_replace("%", "", $fileName);
        $fileName = str_replace("^", "", $fileName);
        $fileName = str_replace("&", "", $fileName);
        $fileName = str_replace("*", "", $fileName);
        $fileName = str_replace("?", "", $fileName);
        $fileName = str_replace("'","",$fileName);
        $fileName = str_replace("\"","",$fileName);
        $fileName = str_replace("\\","",$fileName);
        $fileName = str_replace("/","",$fileName);
        $fileName = str_replace(" ","",$fileName);
        $fileName = str_replace("(","",$fileName);
        $fileName = str_replace(")","",$fileName);
        $fileName = strtolower($fileName);
        return($fileName);
    }




    // uploadImage WITHOUT image processing
    public function uploadFile($file_input, $file_output_name, $destination_path)
    {    
        //Connection to the S3 Bucket
        $client = \Aws\S3\S3Client::factory(array(
            'version'     => 'latest',
            'region'      => getenv('S3_REGION'),
            'credentials' => [
                'key'    => getenv('S3_KEY'),
                'secret' => getenv('S3_SECRET'),
            ],
        ));

        // Upload an object to Amazon S3
        $result = $client->putObject(array(
            'Bucket' => getenv('S3_BUCKET'),
            'Key'    => getenv('S3_ROOT').$destination_path.'/'.$file_output_name,
            'Body'   => file_get_contents($file_input),
            'ACL'   => 'public-read',
            'ContentType'  => 'application/pdf'
        ));                      
    } 



    // uploadImage WITH image processing
    public function uploadAfterImgProcess($file_input, $file_output_name, $destination_path)
    {    
        //Connection to the S3 Bucket
        $client = \Aws\S3\S3Client::factory(array(
            'version'     => 'latest',
            'region'      => getenv('S3_REGION'),
            'credentials' => [
                'key'    => getenv('S3_KEY'),
                'secret' => getenv('S3_SECRET'),
            ],
        ));

        // Upload an object to Amazon S3
        $result = $client->putObject(array(
            'Bucket' => getenv('S3_BUCKET'),
            'Key'    => getenv('S3_ROOT').$destination_path.'/'.$file_output_name,
            'Body'   => $file_input,
            'ACL'   => 'public-read'
        ));                      
    } 



    // upload image to gallery + resize + create thumbnails 
    public function uploadResize($image, $desired_dest, $thumb_dest, $max_desired_width, $max_desired_height, $max_thumb_width, $max_thumb_height)
    {   

     //Renaming the image with the current time
     $imageFileName = rand(0,10000).'_'.time().'_'.$image->getClientOriginalName();
     //fix the name of the image
     $imageFileName = $this->fixFileName($imageFileName);

     // get the image 
     $image_original = $this->imageManager->make($image);
     $image_thumb = $image_original;  
     // get the width and height of the image
     $width = $image_original->width();
     $height = $image_original->height();

     

     // =========== MAX DESIRED WIDTH IMAGE =======

     // if the max desired height is NULL => widen the image
     if($max_desired_height == NULL)
     {
         // if the image is wider than $max_desired_width
         if($width > intval($max_desired_width))
         $image_orignal = $image_original->widen(intval($max_desired_width)); // resize to specified max width
         else
         $image_orignal = $image_original->resize($width, $height); //resize using the same size just for compression.
     }

     // if the max desired width is NULL => heightem the image
     else if($max_desired_width == NULL)
     {
         // if the image is taller than $max_desired_height
         if($height > intval($max_desired_height))
         $image_orignal = $image_original->heighten(intval($max_desired_height)); // resize to specified max height
         else
         $image_orignal = $image_original->resize($width, $height); //resize using the same size just for compression.
     }


      // convert the output of the image for S3 bucket upload       
     $image_orignal =  $image_orignal->stream()->__toString();
     // upload the original image to the s3 bucket
     $this->uploadAfterImgProcess($image_orignal, $imageFileName, $desired_dest);

     // =========== THUMB IMAGE =======

     //creating thumbs by resize the image to as a square image (change the variable)
     $image_thumb =  $image_thumb->heighten(intval($max_thumb_height))->crop(intval($max_thumb_width), intval($max_thumb_height)); 
   
     // if($image_original->exif()['Orientation'] != 1) // if the image is in portrait mode
     //   $image_thumb =  $image_thumb->rotate(-90); //rotate the image by 90 degree to adjust its orientation 
      
      
     // upload the original image to the s3 bucket
     $image_thumb =  $image_thumb->stream()->__toString();
     // upload the thumbn image to the s3 bucket
     $this->uploadAfterImgProcess($image_thumb, $imageFileName, $thumb_dest);

     // return the name of the processed images
     return $imageFileName;
    }



    // test if input has file, upload file and return image name -  FOR ADD
    public function fillInputWithImageForAdd(Request $request, $input_name, $desired_dest, $thumb_dest, $max_desired_width, $max_desired_height, $max_thumb_width, $max_thumb_height)
    {

      // logo upload if exist
      if ($request->hasFile($input_name)) {

            //Getting the images from the form
            $image = $request->file($input_name);

            // process the image, compress and resize to create original img and thumb img. Return the image name
            $imageFileName = $this->uploadResize($image, $desired_dest, $thumb_dest, $max_desired_width, $max_desired_height, $max_thumb_width, $max_thumb_height);

            // get the name of the uploaded image 
            $img_name = $imageFileName;
          }

        else // if we didn't change the old image
            $img_name = NULL;

        // return the name of the processed images    
        return $img_name;
    }



    // test if input has file, upload file and return image name - FOR EDIT (GET INFO DETAILS FROM QUERY BEFORE)
    public function fillInputWithImageForEdit(Request $request, $input_name, $desired_dest, $thumb_dest, $max_desired_width, $max_desired_height, $max_thumb_width, $max_thumb_height, $old_img_name)
    {

      // logo upload if exist
      if ($request->hasFile($input_name)) {

            //Getting the images from the form
            $image = $request->file($input_name);

            // process the image, compress and resize to create original img and thumb img. Return the image name
            $imageFileName = $this->uploadResize($image, $desired_dest, $thumb_dest, $max_desired_width, $max_desired_height, $max_thumb_width, $max_thumb_height);

            // get the name of the uploaded image 
            $img_name = $imageFileName;
          }

        else // if we didn't change the old image, replace img_name by the old image that we got from the query
            $img_name = $old_img_name;

        // return the name of the processed images    
        return $img_name;
    }



    // test if input has file, upload file and return image name
    public function fillInputWithFileForAdd(Request $request, $input_name, $s3_bucket_output)
    {
      // check if we have uploaded a photo for the candidate
      if ($request->hasFile($input_name)) {

          //Getting the image from the form
          $image = $request->file($input_name);
          //Renaming the image with the time
          $imageFileName = rand(0,10000).'_'.time().'_'.$image->getClientOriginalName();
          //Rename the file by fixing white spaces
          $imageFileName = $this->fixFileName($imageFileName);
          // upload the image to the users folder in the s3 bucket
          $this->uploadFile($image, $imageFileName, $s3_bucket_output);

          // get the name of the uploaded image and return it with the ajax response
          $file_name = $imageFileName;
        }

        else // if we didn't upload an image
          $file_name = NULL;


          return $file_name;
    }



     // test if input has file, upload file and return image name
    public function fillInputWithFileForEdit(Request $request, $input_name, $s3_bucket_output)
    {
      // check if we have uploaded a photo for the candidate
      if ($request->hasFile($input_name)) {

          //Getting the image from the form
          $image = $request->file($input_name);
          //Renaming the image with the time
          $imageFileName = rand(0,10000).'_'.time().'_'.$image->getClientOriginalName();
          //Rename the file by fixing white spaces
          $imageFileName = $this->fixFileName($imageFileName);
          // upload the image to the users folder in the s3 bucket
          $this->uploadFile($image, $imageFileName, $s3_bucket_output);

          // get the name of the uploaded image and return it with the ajax response
          $file_name = $imageFileName;
        }

        else // if we didn't upload an image
   
         $file_name = false;


        return $file_name;
    }



     // upload product image with 3 format (thumbnails, medium, large) - ADD and EDIT
    public function uploadProduct($old_image, $request, $input_name, $large_dest, $medium_dest, $thumb_dest, $large_desired_width, $large_desired_height, $medium_desired_width, $medium_desired_height, $max_thumb_width,  $max_thumb_height)
    {   
        // check if we have uploaded a new image
        if ($request->hasFile($input_name)) 
        { 
          //Getting the images from the form
          $image = $request->file($input_name);
          // process the image, compress and resize to create original img and thumb img. Return the image name

         //Renaming the image with the current time
         $imageFileName = rand(0,10000).'_'.time().'_'.$image->getClientOriginalName();
         //fix the name of the image
         $imageFileName = $this->fixFileName($imageFileName);

         // get the image 
         $image_original = $this->imageManager->make($image);
         $medium_img = $image_original;
         $thumb_img = $image_original;  
         // get the width and height of the image
         $width = $image_original->width();
         $height = $image_original->height();

         

         // =========== LARGE DESIRED DIMENSIONS =======

         // if the max desired height is NULL => widen the image
         if($large_desired_height == NULL)
         {
             // if the image is wider than $large_desired_width
             if($width > intval($large_desired_width))
             $large_img = $image_original->widen(intval($large_desired_width)); // resize to specified max width
             else
             $large_img = $image_original->resize($width, $height); //resize using the same size just for compression.
         }

         // if the max desired width is NULL => heightem the image
         else if($large_desired_width == NULL)
         {
             // if the image is taller than $large_desired_height
             if($height > intval($large_desired_height))
             $large_img = $image_original->heighten(intval($large_desired_height)); // resize to specified max height
             else
             $large_img = $image_original->resize($width, $height); //resize using the same size just for compression.
         }

         // convert the output of the large image for S3 bucket and upload    
         $large_img =  $large_img->stream()->__toString();
         $this->uploadAfterImgProcess($large_img, $imageFileName, $large_dest); 


         // =========== MEDIUM DESIRED DIMENSIONS =======

         // if the max desired height is NULL => widen the image
         if($medium_desired_height == NULL)
         {
             // if the image is wider than $medium_desired_width
             if($width > intval($medium_desired_width))
             $medium_img = $medium_img->widen(intval($medium_desired_width)); // resize to specified max width
             else
             $medium_img = $medium_img->resize($width, $height); //resize using the same size just for compression.
         }

         // if the max desired width is NULL => heightem the image
         else if($medium_desired_width == NULL)
         {
             // if the image is taller than $medium_desired_height
             if($height > intval($medium_desired_height))
             $medium_img = $medium_img->heighten(intval($medium_desired_height)); // resize to specified max height
             else
             $medium_img = $medium_img->resize($width, $height); //resize using the same size just for compression.
         }


         // convert the output of the medium image for S3 bucket and upload 
         $medium_img =  $medium_img->stream()->__toString();
         $this->uploadAfterImgProcess($medium_img, $imageFileName, $medium_dest);


         // =========== THUMB IMAGE =======

         //creating thumbs by resize the image to as a square image (change the variable)
         $thumb_img =  $thumb_img->heighten(intval($max_thumb_height))->crop(intval($max_thumb_width), intval($max_thumb_height)); 
       
         // if($image_original->exif()['Orientation'] != 1) // if the image is in portrait mode
         //   $thumb_img =  $thumb_img->rotate(-90); //rotate the image by 90 degree to adjust its orientation 


         // convert the output of the thumb image for S3 bucket and upload 
         $thumb_img =  $thumb_img->stream()->__toString();
         $this->uploadAfterImgProcess($thumb_img, $imageFileName, $thumb_dest);

         // return the name of the processed images
         return $imageFileName;

        }
        // no image has been uploaded
        else
            return $old_image;
    }

  

}
