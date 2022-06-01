# Burp行为检测
## 原理
开启burp代理时，浏览器能够访问burp证书下载地址，http://burp 。

使用图片进行跨域请求http://burp/favicon.ico 确认开启了burp，并向api/index.php发起请求记录Hacker ip。
[![XJ9XeP.png](https://s1.ax1x.com/2022/06/01/XJ9XeP.png)](https://imgtu.com/i/XJ9XeP)
[![XJ9KqP.png](https://s1.ax1x.com/2022/06/01/XJ9KqP.png)](https://imgtu.com/i/XJ9KqP)
[![XJ91IS.jpg](https://s1.ax1x.com/2022/06/01/XJ91IS.jpg)](https://imgtu.com/i/XJ91IS)
由于对Java web不是很熟悉，Java代码没有完全实现捕捉请求解析真实ip的功能，也不方便部署，就找了github上php的代码进行了修改。

## 参考文章/代码
https://github.com/zhangxiaoq00/IPvalidate
https://mp.weixin.qq.com/s/V0WdN9CMrTqo6qInuwyR6g
