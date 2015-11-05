Zfegg 管理应用程序基础框架
==============================

依赖工具
------------

请查看 [composer.json](composer.json) 文件.

安装
------------

### 通过 Composer (create-project)

你可以使用 [Composer](http://getcomposer.org/) 的 `create-project` 命令来创建你的项目
 (你必须先安装 [composer.phar](https://getcomposer.org/doc/00-intro.md#downloading-the-composer-executable)):

```bash
curl -s https://getcomposer.org/installer | php --
composer create-project -sdev zfegg/zfegg-admin-skeleton path/to/install
```

### 通过 Git (clone)

First, clone the repository:

```bash
git clone https://github.com/zfegg/zfegg-admin-skeleton.git # optionally, specify the directory in which to clone
cd path/to/install
```

克隆或下载后, 你必须使用 [Composer](https://getcomposer.org/) 来安装依赖.
如果已经有 Composer:

```bash
composer install
```

### All methods

基础安装后, 你需要开启开发者模式:

```bash
cd path/to/install
php public/index.php development enable # put the skeleton in development mode
```

现在启动它, 需要做下列之一:

- 在你的Web服务(Apache/Nginx/...)创建一个 vhost, 项目文档目录(DocumentRoot) 设置在 
  `public/`
- 使用 PHP (5.4.8+) 自带的Web服务 (**note**: 不要在线上版本使用!)

使用后者的执行方式:

```bash
cd path/to/install
php -S 0.0.0.0:8080 -ddisplay_errors=0 -t public public/index.php
```

你可以通过 http://localhost:8080/ 访问, 看到登录页面

### 执行数据库文件

你需要创建个数据库, 导入数据表结构(数据表文件: `vendor/zfegg/zfegg-admin/data/zfegg-admin.sql` )

### 复制后台配置范例

复制一份 `vendor/zfegg/zfegg-admin/config/zfegg-admin.local.php.dist`  到 
`config/autoload/zfegg-admin.local.php`

线上环境复制到 `config/autoload/zfegg-admin.global.php`

### NOTE ABOUT USING APACHE

```apache
AllowEncodedSlashes On
```

This change will need to be made in your server's vhost file (it cannot be added to `.htaccess`).

### 注意: 关于 OPCACHE

**禁用所有opcode 缓存当你在本地开发!**

When you are ready to deploy your API to **production**, however, you can
disable development mode, thus disabling the admin interface, and safely run an
opcode cache again. Doing so is recommended for production due to the tremendous
performance benefits opcode caches provide.

### NOTE ABOUT DISPLAY_ERRORS

The `display_errors` `php.ini` setting is useful in development to understand what warnings,
notices, and error conditions are affecting your application. However, they cause problems for APIs:
APIs are typically a specific serialization format, and error reporting is usually in either plain
text, or, with extensions like XDebug, in HTML. This breaks the response payload, making it unusable
by clients.

For this reason, we recommend disabling `display_errors` when using the Apigility admin interface.
This can be done using the `-ddisplay_errors=0` flag when using the built-in PHP web server, or you
can set it in your virtual host or server definition. If you disable it, make sure you have
reasonable error log settings in place. For the built-in PHP web server, errors will be reported in
the console itself; otherwise, ensure you have an error log file specified in your configuration.

`display_errors` should *never* be enabled in production, regardless.
