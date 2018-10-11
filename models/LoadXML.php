<?php
namespace models;

class LoadXML
{
    private
        $_url,
        $_stream,
        $_filePath = 'cache',
        $_cacheFile,
        $_actuallyCache = false;

    public function __construct($url)
    {
        $this->_url = $url;
        $this->_cacheFile = $this->_filePath .DIRECTORY_SEPARATOR .self::getFileNameByUrl();
        $this->_stream = self::checkCacheStream()->getXMLData();
    }

    public function checkCacheStream(){

        if(file_exists($this->_cacheFile)){

            if((time() - filemtime($this->_cacheFile)) < 300)
                $this->_actuallyCache = true;
        }

        return $this;
    }

    public function getXMLData(){

        return $this->_actuallyCache ? file_get_contents($this->_cacheFile) : self::_getXMLFromUrl();
    }

    public function getFileNameByUrl(){

        return substr(md5($this->_url), 0, 12);
    }

    public function checkFormat(){

        $pattern = '~\<\?xml.*\?\>\s*?\<\!DOCTYPE\s+yml_catalog.*\"shops.dtd\"\>~i';
        if(preg_match($pattern, $this->_stream) != 1)
            throw new \Exception("Не является XML форматом!");

        return $this;
    }

    // TODO: сделать функцию удаления всех устаревших файлов
    public function saveStream(){

        if(!$this->_actuallyCache){

            if(!file_put_contents($this->_cacheFile, $this->_stream))
                throw new \Exception("Не удается сохранить поток в файл!");
        }

        return $this;
    }

    public function getXMLObject(){

        return simplexml_load_string($this->_stream);
    }

/*    private function _getFileNameByFileHashContent($content){

        $tmp = tmpfile();
        fwrite($tmp, $content);
        $filename = self::getFileNameByFileHash(
            stream_get_meta_data($tmp)['uri']
        );
        fclose($tmp);

        return $filename;
    }*/

    private function _getXMLFromUrl(){

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->_url);
        curl_setopt($ch, CURLOPT_VERBOSE, false);
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