<?php
namespace Tools\Filesworks;

class Files {

    public function file_upload($filetmp, $filename, $path, $dir = "")
    {
        // print_r($filetmp); print_r($filename);
        if ($filetmp != "") {

            $new_file_name = $this->is_exists_file($filename, $path);

            // echo $dir . $path . $new_file_name;

            $file_type = explode('.', $new_file_name);
            $file_type = end($file_type);

            // echo $file_type;

            $allowed_file_types = ['jpg', 'png', 'gif','jpeg',
                'doc', 'docx', 'xls','xlsx','pdf',
                'webp', 'project', 'txt',
                'avi', 'mp4', 'asf', 'mov', 'mpg', 'mpeg', 'wmv', 'm4v'];

            if (! (in_array(strtolower($file_type), $allowed_file_types) )) exit ('Bad file type!');

            move_uploaded_file($filetmp, $dir . $path . $new_file_name);

            if ($this->is_exists_file($new_file_name, $path)) return $new_file_name;
            else {
                rename($_SERVER["DOCUMENT_ROOT"] . $path . $new_file_name, $dir . $path . "error_file");
                return "error_file";
            }
        }
    }

    public function file_delete($path_file, $dir = "")
    {
        return unlink($dir . $path_file);
    }

    //Check file if exists and give new name if it is copy.
    public function is_exists_file($filename, $path, $dir = "")
    {
        $i = 0;
        $new_file_name = $filename;
        $unique_name_find = false;
        while ($unique_name_find != true) {
            if (file_exists($dir . $path . $new_file_name)) {
                $new_file_name = "copy" . $i . "_" . $filename;
            } else {
                $unique_name_find = true;
            }
            $i++;
        }
        return $new_file_name;
    }

}
