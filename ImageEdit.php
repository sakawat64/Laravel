<?php
use Image; //make sure install Image Intervation
$product_dt = Product::Where('id', $id)->first();
        $images=explode(',', $product_dt->product_image);
        if ($request->hasFile('images'))
        {
            $images=array();
            foreach(explode(',', $product_dt->product_image) as $sin_image){
                $impath = "uploads/products/".$sin_image;
                if(file_exists($impath)){
                    @unlink($impath);
                }
            }
            $files = $request->file('images');
            foreach($files as $file){
            $extension = $file->getClientOriginalExtension();
            $fileName = Str::random(5).time() .".". $extension;
            $img = Image :: make($file)->resize(720,480);
            $upload_path = public_path()."/uploads/products/";
            $img->save($upload_path.$fileName);
            $images[] = $fileName;
            }
        }
        $thumbleName = $product_dt->thumble;
        if($request->hasFile('thumble'))
        {
            $oldth = "uploads/products/thumble/".$product_dt->thumble;
            if(file_exists($oldth)){
                @unlink($oldth);
            }
            $thumble = $request->file('thumble');
            $extension_th = $thumble->getClientOriginalExtension();
            $thumbleName = Str::random(6).time() .".". $extension_th;
            $img_th = Image :: make($thumble)->resize(720,480);
            $upload_pathth = public_path()."/uploads/products/thumble/";
            $img_th->save($upload_pathth.$thumbleName);
        }
?>