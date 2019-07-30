
# one框架项目

[框架文档 https://www.kancloud.cn/vic-one/php-one/826876](https://www.kancloud.cn/vic-one/php-one/826876)

## 环境变量配置文件
请将.env.example文件拷贝并命名为.env

.env文件为各环境的本地配置, 用于定义环境标识符及敏感数据(数据库ip,密码...)等. 
该配置文件也可能被用于其他组件(非PHP),请谨慎删除配置项. 
该文件须加入.gitignore忽略名单.

以下文件根据.env文件中定义的environment变量动态加载.用于定义各环境非敏感数据的配置, 如: 开发环境设置错误邮件接收者仅为自己而生产需要设置其他接收者; 开发及测试环境需要设置debug模式而生产不需要等等. 
请不要将这些文件(如有更多环境可相应增加)加入.gitignore忽略名单
- .env.development 开发环境
- .env.test 测试环境(251)
- .env.simulation 仿真环境(76) 
- .env.production 生产环境

项目中直接使用getenv()函数获取配置项, 请参阅 [https://github.com/vlucas/phpdotenv](https://github.com/vlucas/phpdotenv)。