<?php

namespace App;

use Auth;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;


class Helper
{
    public static function getInfo($ip, $oid, $community = 'public')
    {
        try {
            $info = \snmpget($ip, $community, $oid);
            $info = explode(":", $info);
            $info = str_replace('(', '', $info[1]);
            $info = str_replace('"', '', $info);
            if ($info[0] == "Hex-STRING") {
                return "";
            }
            return trim($info);
        } catch (\Exception $e) {
            return $e->getMessage();
        }


    }


    /**
     * @return string
     * Used to get path to store avatar
     */
    public static function avatar()
    {
        if (Auth::user()->avatar) {
            return asset('avatars/' . Auth::user()->avatar);
        } else {
            return asset('avatars/placeholder.png');
        }

    }

    /**
     * @param string $path
     * @return mixed Used to generate download paths
     * Used to generate download paths
     */
    public static function downloadPath($path = '')
    {
        $str = public_path();
        $str = str_replace("\\application\\public", "", $str);
        $str = str_replace('/application/public', "", $str);
        return $str . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }

    public static function paginateQuery(
        $query,
        $count,
        $limit,
        $page,
        $queryString,
        $path
    )
    {
        $paginator = new Paginator($query, $count, $limit, $page, [
            'query' => $queryString
        ]);
        $paginator->setPath($path);
        return $paginator;
        /*
                $paginator = new Paginator($supplier, $this->getSuppliersCount(), env('RECORDS_VIEW'), null, [
                    'query' => $params['sort']
                ]);
                $paginator->setPath('/supplier');
                return $paginator;
        */
    }

    /**
     * @param $data
     * Saves barcode to png
     */
    public static function saveBarcode($data)
    {
        /*
        list($type, $data) = explode(';', $data);
        list(, $data) = explode(',', $data);
        $data = base64_decode($data);
        */
        $path = storage_path() . '/fucklife.png';
        file_put_contents($path, base64_decode($data));
    }

    public static function fontawesomeArray()
    {
        $path = base_path() . '/public/assets/css/font-awesome.css';
        $class_prefix = 'fa-';
        $pattern = '/\.(' . $class_prefix . '(?:\w+(?:-)?)+):before\s+{\s*content:\s*"(.+)";\s+}/';
        $subject = file_get_contents($path);

        preg_match_all($pattern, $subject, $matches, PREG_SET_ORDER);

        $icons = array();

        foreach ($matches as $match) {
            $icons[$match[1]] = $match[1];
        }

        //  $icons = var_export($icons, TRUE);
        //$icons = stripslashes($icons);

        return $icons;
    }


    /**
     * @param $d
     * @return array
     * Converts Query builder data to array
     */
    public static function objectToArray($d)
    {
        if (is_object($d)) {
            // Gets the properties of the given object
            // with get_object_vars function
            $d = get_object_vars($d);
        }

        if (is_array($d)) {
            /*
             * Return array converted to object
             * Using __FUNCTION__ (Magic constant)
             * for recursive call
             */
            return array_map([__CLASS__, __METHOD__], $d);
        } else {
            // Return array
            return $d;
        }
    }

    /**
     * @return mixed
     * Used to generate download paths
     */
    public static function downloadPathWithFolder($folder)
    {
        $str = public_path();
        $str = str_replace("\\application\\public", "", $str);
        $str = str_replace('/application/public', "", $str);
        return $str . "/" . $folder;
    }

    /**
     * @param $englishWord
     * @param $file
     * @param $limit
     * @param string $placement
     * @return string
     * Use as Below
     *  {!! \App\Helper::translateAndShorten('test','dashboard',2,'top')!!}
     */
    public static function translateAndShorten($englishWord, $file, $limit, $placement = 'top')
    {
        $translationFile = $file . '.' . $englishWord;
        $translation = trans($translationFile);
        return '<span class="translate" data-toggle="tooltip" data-placement="' . $placement . '" title="' . $translation . '" >' . str_limit($translation, $limit) . '</span>';
    }

    public static function popover($title, $content)
    {

        return 'data-container="body" data-toggle="popover" title="' . $title . '" data-content="' . $content . '" trigger="hover"';
    }

    public static function checked($size)
    {
        $page = Setting::firstOrCreate(['userId' => Auth::user()->id]);
        if ($page->paginationDefault == $size) {
            return 'checked=true';
        }
    }
}