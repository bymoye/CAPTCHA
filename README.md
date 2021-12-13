# 该库已并入[CAPTCHA](https://github.com/bymoye/Sakura/blob/master/inc/classes/CAPTCHA.php) 后续不再维护

# CAPTCHA
本仓库用来存储测试验证码

## 思路
利用时间戳作为mt_srand种子

利用伪随机数在相同机器、相同种子上以相同的方法生成的内容也相同 作为后端验证验证码是否正确

已实现 

将用session来存储时间戳而不是验证码 

后端判断时间戳是否已超过一分钟

超过则返回失效 

待优化 
