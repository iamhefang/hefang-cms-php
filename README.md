# hefang-cms-php
高性能内容管理系统

## 配置项说明

1. `object`: 对象
1. `number`: 数字
1. `textarea`: 多行文本
1. `text`: 单行文本
1. `html`: HTML
1. `boolean`: 布尔
1. `image`: 图片
1. `code`: 代码
1. `checkbox`: 多选框
1. `radio`: 单选框
1. `date`: 日期
1. `datetime`: 日期时间
1. `time`: 时间
1. `range`：范围数字

## query说明

字段名=值&字段名!=值|字段名~=值|字段名>值|字段名<值|字段名>=值|字段名<=值

1. `=`: 全等
1. `!=`: 不等
1. `~=`: 包含
1. `>`: 大于
1. `<`: 小于
1. `>=`: 大于等于
1. `<=`: 小于等于
1. `&`: 且
1. `|`: 或

```rest
/api/content/article/list.json?query=title~=debug&(postTime>=2020-06-06|keywords=debug)
```
```sql
select * from article where title like '%debug%' and (post_time >= '2020-06-06' or keywords='debug')
```


## sort 说明
```rest
/api/content/article/list.json?sort=-read_count,+oppose_count&query=title~=debug&(postTime>=2020-06-06|keywords=debug)
```
```sql
select * from article order by read_count desc, oppose_count asc
```
