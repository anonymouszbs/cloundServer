<?php
/**
 * 文件夹文件拷贝
 *
 * @param string $src 来源文件夹
 * @param string $dst 目的地文件夹
 * @return bool
 */
function dir_copy($src = '', $dst = '')
{
    if (empty($src) || empty($dst))
    {
        return false;
    }
 
    $dir = opendir($src);
    dir_mkdir($dst);
    while (false !== ($file = readdir($dir)))
    {
        if (($file != '.') && ($file != '..'))
        {
            if (is_dir($src . '/' . $file))
            {
                dir_copy($src . '/' . $file, $dst . '/' . $file);
            }
            else
            {
                copy($src . '/' . $file, $dst . '/' . $file);
            }
        }
    }
    closedir($dir);
 
    return true;
}
 
 
/**
 * 创建文件夹
 *
 * @param string $path 文件夹路径
 * @param int $mode 访问权限
 * @param bool $recursive 是否递归创建
 * @return bool
 */
function dir_mkdir($path = '', $mode = 0777, $recursive = true)
{
    clearstatcache();
    if (!is_dir($path))
    {
        mkdir($path, $mode, $recursive);
        return chmod($path, $mode);
    }
 
    return true;
}
 


?>