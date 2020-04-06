<?php 
//Корень сайта
$root = rtrim($_SERVER['DOCUMENT_ROOT']);
//Корневой каталог с галереями фото             
$rootDir = 'foto';   

            $url_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $routes = trim($url_path, '/');
            $routesArray = explode('/', $routes);
            $dir = array_pop($routesArray);  
            //Получаем полный путь к директории
            $path_full = $root.'/'.$rootDir.'/'.$dir; 
            //Относительный путь
            $path = '/'.$rootDir.'/'.$dir.'/';
           
            //Сканируем файлы в папке 
            if (is_dir($path_full)) {
                $files = scandir($path_full);               
            }else 
                {
                    return FALSE;                                   
            }          
            
            //Убераем не нужные значения . и .. из массива
            $files = array_diff($files, array('.', '..'));           
            //Формируем массив по дате добаления. В качестве ключей имя файла, в качестве значения - метка времени добавления.
            //В начале массива последние добавленные фото
                foreach ($files as $key=>$file) {                
                        $array[$path.$file] = filemtime($path_full.'/'.$file);
                    }
                //Сортируем полученный массив
                    arsort($array);
                //Получаем ключи массива в которых указаны названия изображений
                    $images = array_keys($array);
                   

                    
                          