<?php
namespace models;

class LoadXML
{
    private
        $_stream,
        $_filePath = 'cache',
        $_cacheFile;

    public function __construct($url)
    {
        $this->_stream = self::_getXMLFromUrl($url);
    }

    public function getFileNameByFileHash($file){

        return substr(md5_file($file), 0, 12);
    }

    public function checkFormat(){

        $pattern = '~\<\?xml.*\?\>\s*?\<\!DOCTYPE\s+yml_catalog.*\"shops.dtd\"\>~i';
        if(preg_match($pattern, $this->_stream) != 1)
            throw new \Exception("Не является XML форматом!");

        return $this;
    }

    public function saveStream(){

        $this->_cacheFile = $this->_filePath .DIRECTORY_SEPARATOR .self::_getFileNameByFileHashContent($this->_stream);

        if(!file_exists($this->_cacheFile)){

            if(!file_put_contents($this->_cacheFile, $this->_stream))
                throw new \Exception("Не удается сохранить поток в файл!");
        }

        return $this;
    }

    public function unsetStreamData(){

        unset($this->_stream);

        return $this;
    }

    public function getXMLObject(){

        $xml = file_get_contents($this->_cacheFile);
        return simplexml_load_string($xml);
    }

    private function _getFileNameByFileHashContent($content){

        $tmp = tmpfile();
        fwrite($tmp, $content);
        $filename = self::getFileNameByFileHash(
            stream_get_meta_data($tmp)['uri']
        );
        fclose($tmp);

        return $filename;
    }

    private function _getXMLFromUrl($url){

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $data = curl_exec($ch);

        self::_checkUrl(curl_getinfo($ch,CURLINFO_HTTP_CODE));
        curl_close($ch);

        return $data;
    }

    private function _checkUrl($code){

        $code = (int)$code;

        if($code != 200 && $code != 301)
            throw new \Exception("Не удалось получить информацию по ссылке! Код: {$code}");
    }
}