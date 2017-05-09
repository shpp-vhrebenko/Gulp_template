module.exports = function() {  
var path = {
    build: { //Тут мы укажем куда складывать готовые после сборки файлы
        html: 'dist/',
        js: 'dist/scripts/',
        css_dist: 'dist/styles/',
        img: 'dist/img/',
        fonts: 'dist/styles/fonts/',
        less: 'dist/styles/',
        php: 'dist/'
    },
    src: { //Пути откуда брать исходники
        php: '*.php',
        html: 'app/*.html', //Синтаксис src/*.html говорит gulp что мы хотим взять все файлы с расширением .html
        js: 'scripts/*.js',//В стилях и скриптах нам понадобятся только main файлы        
        style: 'app/style/main.css',
        img: 'img/**/*.*', //Синтаксис img/**/*.* означает - взять все файлы всех расширений из папки и из вложенных каталогов
        fonts: 'styles/fonts/**/*.{eot,svg,ttf,woff,woff2}',
        less_src: 'styles/main.less'
    },
    watch: { //Тут мы укажем, за изменением каких файлов мы хотим наблюдать
        php: '*.php',
        php_lib: 'lib/*.php',
        php_configs: 'configs/*.php',        
        js: 'scripts/**/*.js',        
        style: 'styles/**/*.css',
        style_less: 'styles/**/*.less',
        img: 'img/**/*.*',
        fonts: 'styles/fonts/**/*.*'
    },
    clean: 'dist/'
};
  return path;
};
