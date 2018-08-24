# crud
easy crud<br>
//第一次使用github<br>
基础环境：php、MySQL、Apache等<br>
版本没有特别限制<br>
其他文件有bootstrap和Fontawesome

# 数据库示例
CREATE TABLE `tabletest` (<br>
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '1',<br>
  `fname` varchar(50) NOT NULL COMMENT '2',<br>
  `lname` varchar(50) NOT NULL COMMENT '3',<br>
  `fullname` varchar(50) NOT NULL COMMENT '4',<br>
  `info` varchar(100) NOT NULL COMMENT '5',<br>
  `addtime` datetime NOT NULL COMMENT '6',<br>
  PRIMARY KEY (`id`)<br>
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;<br>

insert into `tabletest`(`id`,`fname`,`lname`,`fullname`,`info`,`addtime`) values(<br>
"1","zhang","san","zhang san","manager","2018-06-21 13:00:00"),(<br>
"2","li","si","li si","sales","2018-06-21 13:00:00"),(<br>
"3","wang","wu","wang wu","hr","2018-06-21 13:00:00");<br>

# 初始化示例
require("func.php");<br>
	$tt = new ntable();<br>
	$thv = array("序号","列1","列2","编辑");<br>
	$fnum = array(1,2,5);<br>
	$tt -> headTable($thv,"table table-bordered displays");<br>
	$tt -> bodyTable("tabletest",0,0,0,1,1,$fnum);<br>
	$tt -> endTable();<br>
	$tt -> headmodal(0,"编辑数据");

# 输出显示示例
www.mystylelife.cn/crud
测试目前还在组织中，待完善。。。
