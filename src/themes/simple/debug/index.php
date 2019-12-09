<!-- debug -->
<div class="debug-info-container">
	<style>
		.debug-info-container {

		}

		.debug-info-container table {
			width: 100%;
			border-collapse: collapse;
		}

		.debug-info-item-container {
			padding: 8px;
			max-height: 200px;
			overflow: auto;
		}

		.debug-info-container th, .debug-info-container td {
			border: thin solid gray;
			max-height: 100px;
			overflow: auto;
		}

		.debug-info-toggle {
			position: fixed;
			right: 0;
			bottom: 0;
			margin: 20px;
			background: red;
			border-radius: 50%;
			height: 60px;
			width: 60px;
		}

		.debug-info {
			height: 93vh;
			overflow: auto;
			box-shadow: 0 0 10px black;
			padding: 10px;
		}
	</style>
	<div class="debug-info">
		<table>
			<tr>
				<th>服务器操作系统</th>
				<td>{debug[serverOS]}</td>
				<th>服务器名称</th>
				<td>{debug[serverName]}</td>
				<th>服务器地址</th>
				<td>{debug[serverHost]}</td>
				<th>本次请求时间</th>
				<td>{debug[duration]}毫秒</td>
			</tr>
		</table>

		<table>
			<tr>
				<th style="width: 200px">执行的SQL语句</th>
				<td>
					<div class="debug-info-item-container">
						<ol>
							<?php foreach ($debug['executedSQL'] as $item) { ?>
								<li><?= $item->getSql() ?></li>
							<?php } ?>
						</ol>
					</div>
				</td>
			</tr>
			<tr>
				<th style="width: 200px">已定义的类</th>
				<td>
					<div class="debug-info-item-container">
						<ol>
							<?php foreach ($debug['declaredClasses'] as $item) { ?>
								<li>{:item}</li>
							<?php } ?>
						</ol>
					</div>
				</td>
			</tr>
			<tr>
				<th>已加载的文件</th>
				<td>
					<div class="debug-info-item-container">
						<ol>
							<?php foreach ($debug['includedFiles'] as $item) { ?>
								<li>{:item}</li>
							<?php } ?>
						</ol>
					</div>
				</td>
			</tr>
			<tr>
				<th>加载的模块</th>
				<td>
					<div class="debug-info-item-container">
						<ol>
							<?php foreach ($debug['loadedModules'] as $item) { ?>
								<li>{:item}</li>
							<?php } ?>
						</ol>
					</div>
				</td>
			</tr>
			<tr>
				<th>加载的插件</th>
				<td>
					<div class="debug-info-item-container">
						<ol>
							<?php foreach ($debug['loadedPlugins'] as $item) { ?>
								<li>{:item}</li>
							<?php } ?>
						</ol>
					</div>
				</td>
			</tr>
		</table>
	</div>
	<button class="debug-info-toggle">Debug</button>
	<script>
       var $info = $('.debug-info');
       $(".debug-info-toggle").on("click", function () {
           $info.toggle();
       });
	</script>
</div>
<!-- /debug -->
