<?php
//核心类库
class ntable {
  //表格的头部，内有3个参数，$thv是数组，示例：array("序号","列1","列2");
  //$tableclass字符串，用于控制表格样式，默认值为displays；
  //$tbodyid字符串，用于输入表格body部分的id，默认为空；
	function headTable( $thv, $tableclass = "displays", $tbodyid = 0 ) {
		echo <<<EOT
<table class='$tableclass'>
	<thead>
		<tr>
		
EOT;
		for ( $i = 0; $i < count( $thv ); $i++ ) {
			$th = $thv[ $i ];
			echo <<<EOT
	<th>$th</th>
		
EOT;
		}
		if ( $tbodyid ) {
			$tid = "id='$tbodyid'";
		} else {
			$tid = "";
		}
		echo <<<EOT
</tr>
	</thead>
	<tbody $tid>
	
EOT;
	}
  //表格的结束
	function endTable() {
		echo <<<EOT
	</tbody>
</table>

EOT;
	}
  //表格body部分：$table为字符串，输入数据表名；
  //$sort为字符串，输入需要排序列名，默认为addtime降序排列
  //$filter为字符串，输入筛选列名，默认值为空不筛选
  //$txt为字符串，输入筛选列的值，默认为空
  //$edit为布尔值，控制表单是否添加最后一列的编辑按钮，默认false不添加，为true添加
  //$del为布尔值，控制表单是否添加最后一列的删除按钮，默认false不添加，为true添加
  //$fnum为数组，控制表单的部分列不用显示，示例：array(1,2)，代表第二列和第三列不用显示
  //$filter2和$txt2同上面的$filter和$txt，增加一项复合筛选
	//补充说明conn.php为连接数据库的文件，请自行设置，这里就不提供源码了
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
				echo <<<EOT
	<tr>
			<td>$id</td>
			
EOT;
				for ( $i = 1; $i < count( $row ); $i++ ) {
					if ( !in_array( $i, $fnum ) ) {
						echo <<<EOT
<td>$row[$i]</td>
			
EOT;
					}
				}
				if ( $edit ) {
					echo "<td>" . cbtn( btnedit, $row[ 0 ], 1, "", edit );
					if ( $del ) {
						echo "&nbsp;" . cbtn( btndel, $table . "__" . $row[ 0 ], 0, "", eraser ) . "</td>";
					}else{
						echo "</td>";
					}
				}elseif($del){
					echo "<td>" . cbtn( btndel, $table . "__" . $row[ 0 ], 0, "", eraser ) . "</td>";
				}
				
				echo <<<EOT
				
</tr>
	
EOT;
			}
		}
	}
	//modal函数头部
	function headmodal( $id = null, $headtxt = null ) {
		if ( $id ) {
			$mm = "myModal" . $id;
		} else {
			$mm = "myModal";
		}
		if(!$headtxt){
			$headtxt = "模态框（Modal）标题";
		}
		echo <<<EOT
		<div class="modal fade" id="$mm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title" id="myModalLabel$id">$headtxt</h4>
					</div>
					<div class="modal-body">
						<div class="row">
EOT;
	}
	//modal函数尾部
	function bootmodal($class=0,$modal=0,$txt=0) {
		if(!$class){
			$class="";
		}else{
			$class="btnup";
		}
		if(!$modal){
			$modal="";
		}else{
			$modal="data-toggle='modal' data-target='#myModal$modal'";
		}
		if(!$txt){
			$txt = "提交更改";
		}
		echo <<<EOT
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
						<button type="button" class="btn btn-primary $class" $modal id="submit">$txt</button>
					</div>
				</div>
			</div>
		</div>

EOT;
	}
	//依据对应信息删除某表格中的某条记录
	function deleTableid( $tablename, $deleid, $filtername = 'id' ) {
		//连接数据库
		require( 'conn.php' );
		//执行删除操作
		$result_query = mysqli_query( $conn, "DELETE FROM $tablename WHERE $filtername = '$deleid'" );
		if ( $result_query ) {
			return true;
		} else {
			return false;
		}
	}
	//新增数据信息
	function insertTable( $tablename, $insertarr, $refresh = null ) {
		include( "conn.php" );
		$result_query = mysqli_query( $conn, "select * FROM $tablename" );
		if ( $result_query ) {
			$arr = array();
			$i = 0;
			while ( $row = mysqli_fetch_field( $result_query ) ) {
				$i++;
				if ( $i > 1 ) {
					$arr[] = $row->name;
				}
			}
		}
		$array1 = array2string1( $arr );
		$array2 = array2string2( $insertarr );
		$result_insert = mysqli_query( $conn, "insert into $tablename ($array1) values ($array2)" );
		if ( $result_insert ) {
			if ( $refresh === 1 ) {
				echo "<script>parent.location.href=''</script>";
			} else {
				return true;
			}
		} else {
			return false;
		}
	}
	//更新数据信息
	function updateTable( $tablename, $updatearr, $id, $refresh = null ) {
		include( 'conn.php' );
		$result_query = mysqli_query( $conn, "select * FROM $tablename" );
		if ( $result_query ) {
			$arr = array();
			$i = 0;
			while ( $row = mysqli_fetch_field( $result_query ) ) {
				$i++;
				if ( $i > 1 ) {
					$arr[] = $row->name;
				}
			}
		}
		$array1 = array2string3( $arr, $updatearr );
		$result_upadate = mysqli_query( $conn, "update $tablename set $array1 where id = '$id'" );
		if ( $result_upadate ) {
			if ( $refresh == 1 ) {
				echo "<script>parent.location.href=''</script>";
			} else {
				return true;
			}
		} else {
			echo "<script>alert('更新信息失败！');parent.location.href=''</script>";
		}
	}
	//更新指定行的一个值
	function updateOnevalue( $tablename, $columname, $value, $filtername, $filtertxt ) {
		include( 'conn.php' );
		$result_update = mysqli_query( $conn, "update $tablename set $columname = '$value' where $filtername = '$filtertxt'" );
		if ( $result_update ) {
			return true;
		} else {
			return false;
		}
	}
}
//生成button的函数，这个按钮提供了一些扩展的可能，首先可以控制页面里面的modal，其次可以设置按钮的id和class，还可以更改按钮的图标
//这里我用的是bootstrap和Fontawesome4.0
function cbtn($btype,$id,$data,$modalid,$icon){
	if($data){
		$data = "data-toggle='modal' data-target='#myModal$modalid'";
	}else{
		$data = "";
	}
	return "<button type='button' class='btn btn-info btn-xs $btype' id='$id' $data><i class='fa fa-$icon'></i></button>";
}
