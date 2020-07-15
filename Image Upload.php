<?php
//use 
use Image;//make sure install image intervation



$images=array();
if ($request->hasFile('images') && $request->hasFile('thumble'))
{
$files = $request->file('images');
foreach($files as $file){
$extension = $file->getClientOriginalExtension();
$fileName = Str::random(5).time() .".". $extension;
$img = Image :: make($file)->resize(720,480);
$upload_path = public_path()."/uploads/products/";
$img->save($upload_path.$fileName);
$images[] = $fileName;
}
//thumble
    $thumble = $request->file('thumble');
    $extension_th = $thumble->getClientOriginalExtension();
    $thumbleName = Str::random(6).time() .".". $extension_th;
    $img_th = Image :: make($thumble)->resize(720,480);
    $upload_pathth = public_path()."/uploads/products/thumble/";
    $img_th->save($upload_pathth.$thumbleName);
}
$data = [
    'product_name' => $request->product_name,
    'product_title' => $request->product_title,
    'category_id' => $request->category_id,
    'product_description' => $request->product_description,
    'product_price' => $request->product_price,
    'feature_status' => 0,
    'product_status' => 1,
    'thumble' => $thumbleName ? $thumbleName : null,
    'product_image' => implode(",",$images),
    'entry_by' => \Auth::guard('admin')->user()->id,
    'update_by'=> \Auth::guard('admin')->user()->id,
]; 
$insert = Product::create($data);
?>