<?php
//核心类库
class ntable {
  //表格的头部，内有3个参数，$thv是数组，示例：array("序号","列1","列2");
  //$tableclass字符串，用于控制表格样式，默认值为displays；
  //$tbodyid字符串，用于输入表格body部分的id，默认为空；
	function headTable( $thv, $tableclass = "displays", $tbodyid = 0 ) {
		echo "<table class='$tableclass'><thead><tr>";
		for ( $i = 0; $i < count( $thv ); $i++ ) {
			$th = $thv[ $i ];
			echo "<th>$th</th>";
		}
		if ( $tbodyid ) {
			$tid = "id='$tbodyid'";
		} else {
			$tid = "";
		}
		echo "</tr></thead><tbody $tid>";
	}
  //表格的结束
	function endTable() {
		echo "</tbody></table>";
	}
  //表格body部分：$table为字符串，输入数据表名；
  //$sort为字符串，输入需要排序列名，默认为addtime降序排列
  //$filter为字符串，输入筛选列名，默认值为空不筛选
  //$txt为字符串，输入筛选列的值，默认为空
  //$edit为布尔值，控制表单是否添加最后一列的编辑按钮，默认false不添加，为true添加
  //$del为布尔值，控制表单是否添加最后一列的删除按钮，默认false不添加，为true添加
  //$fnum为数组，控制表单的部分列不用显示，示例：array(1,2)，代表第二列和第三列不用显示
  //$filter2和$txt2同上面的$filter和$txt，增加一项复合筛选
	function bodyTable( $table, $sort = 0, $filter = 0, $txt = 0, $edit = 0, $del = 0, $fnum = array(), $filter2 = 0, $txt2 = 0 ) {
		require( "conn.php" );
		if ( $sort ) {
			$sortt = "order by $sort";
		} else {
			$sortt = "order by addtime desc";
		}
		if ( $filter ) {
			$re = mysqli_query( $conn, "select*from $table where $filter = '$txt' $sortt" );
		} else {
			$re = mysqli_query( $conn, "select*from $table $sortt" );
		}
		if ( $re && mysqli_num_rows( $re ) > 0 ) {
			$id = 0;
			while ( $row = mysqli_fetch_row( $re ) ) {
				$id += 1;
				echo "<tr><td>$id</td>";
				for ( $i = 1; $i < count( $row ); $i++ ) {
					if ( !in_array( $i, $fnum ) ) {
						echo "<td>" . $row[ $i ] . "</td>";
					}
				}
				if ( $edit ) {
					echo "<td>" . cbtn( btnedit, $row[ 0 ], 1, 0, edit ) . "</td>";
				}
				if ( $del ) {
					echo "<td>" . cbtn( btndel, $table . "__" . $row[ 0 ], 0, 0, eraser ) . "</td>";
				}
				echo "</tr>";
			}
		}
	}
}
