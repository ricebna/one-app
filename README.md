
# one框架项目

[框架文档地址](https://www.kancloud.cn/vic-one/php-one/826876)

安装

```shell
composer create-project lizhichao/one-app:dev-test_rpc
```

## 环境变量配置文件
.env 本机配置, 不同环境可能有不同的变量值, 用于定义环境标识和敏感数据(如数据库ip,密码等)
以下文件用于定义各环境非敏感数据的配置, 如开发环境设置错误邮件接收者仅为自己, 设置debug模式等等, 根据.env文件定义的environment值动态加载.
- .env.development 开发环境
- .env.test 测试环境(251)
- .env.simulation 仿真环境(76) 
- .env.production 生产环境

项目中直接使用getenv()函数获取配置项, 请参阅 [https://github.com/vlucas/phpdotenv](https://github.com/vlucas/phpdotenv)。