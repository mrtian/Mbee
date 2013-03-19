## Mbee  一个简单的前端模块化方案
  Mbee 是一个简单的前端模块化方案，提供浏览端的Commonjs规范的模块加载与书写习惯。但是这并不是seajs,requirejs等其它类似的浏览器模块化方案。该方案完全使用Comonjs规范，没有define,也没有use等seajs提供的接口。    
  简单地说，这只是一个JS编译的方案，会把您书写的js模块打包压缩合并，并支持于浏览器运行而已。

##依赖环境
主要依赖于node,如果您需要使用Mbee用于生产环境，以供自动化debug以及上线，需要PHP及搭建webserver  
###node依赖的模块
uglify-js 用于压缩，使用npm安装即可  


    npm install uglify-js -g


###在node环境使用

    /*脚本示例: node deploy.js -m test.js -o test.min.js  
     * 传入参数：  
            -f 或者无参数: 表示直接压缩文件，简单的压缩js  
            -m 模块压缩：会分析模块依赖，并导入这些依赖，合并进行压缩  
            -p 文件夹压缩  
            -fm 压缩文件并自动添加模块化代码  
            -pm 压缩文件夹并自动添加模块化代码  
            -pcm|-pmc 合并目录并给每个文件添加模块化代码  
            ...  
    */  
    node deploy.js -m test.js -o test.min.js

####每一个js都使用Commonjs规范书写即可。
####test.js示例

    var a = require('module/a');
    a.sayHi();

####module/a.js代码示例

    exports.sayHi = function(){
    	alert('Hello world');
    }

###php自动化调试及线上环境
mbee.php对文件进行了缓存，304等处理。并能自动对比文件新旧，以输出到客户端，保证客户端获取到最新代码。  
####获取mbee源码

    git clone https://github.com/mrtian/mbee.git ~/Sites/Mbee


####注：用于线上环境时，请修改mbee.php中的方法，以避免可能由于集群，文件权限等问题无法使用的情况
####配置webserver
配置你的webserver环境，让你获取js文件的请求使用mbee的入口php来解析。    
如 nginx配置：    

    server {
            listen  80;
            server_name     www.mbee.com;
            root    ~/Sites/Mbee;
            index index.php;
            location ~ / {
                    rewrite ^(.*) /index.php break;
                    fastcgi_pass   127.0.0.1:9090;
                    fastcgi_param  SCRIPT_FILENAME ~/Sites/Mbee/index.php;
                    include fastcgi_params;
            }
     }
   
 打开浏览器：http:://youdomain.com/page/index.js 查看js源码    
 或者在你的页面中添加 http:://youdomain.com/page/index.js 看看效果     

 ####配置mbee
 在入口文件index.php中可以配置开发模式(就是使用不压缩的js而已)        
 
    //Mbee的根目录
    define('MBEE_DIR', dirname(__FILE__));
    //定义脚本目录
    define('MBEE_SCRIPTS_DIR', MBEE_DIR.'/scripts/');
    //debug模式：js不会压缩，以合并后的源码方式输出
    //true: 开启压缩
    define('MBEE_DEBUG', true);

###使用模块代码
####1,模块中使用模块
直接使用 `var a = require('a')` 即可
####2,非模块或者页面中使用模块
`var a = mt.module('a')`;
####3，加载自动执行模块    
`<script type="text/javascript" src="http:://youdomain.com/page/index.js" init="page/index"><script>`    
  

 Enjoy.












