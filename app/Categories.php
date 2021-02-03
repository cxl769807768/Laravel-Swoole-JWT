<?php

namespace App;

use Encore\Admin\Traits\AdminBuilder;
use Encore\Admin\Traits\ModelTree;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Categories extends BaseModel
{

    use ModelTree, AdminBuilder;
    protected $table = 'categories';

    protected $fillable = ['pid', 'cate_name', 'sort'];

//    protected $with = [
//        'parent'
//    ];


    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setParentColumn('pid');  // 父ID
        $this->setOrderColumn('sort'); // 排序
        $this->setTitleColumn('cate_name'); // 标题
    }
    public static function getChild($id){
       return self::query()->where('pid',"=",$id)->get()->toArray();
    }
    /**
     * 该分类的子分类
     */
    public function child()
    {
        return $this->hasMany(get_class($this), 'pid', $this->getKeyName());
    }
    // 该分类下的文章
    public function aritcles()
    {
        return $this->hasMany(Article::class, 'cid', $this->getKeyName());
    }
    // 该分类下的news
    public function news()
    {
        return $this->hasMany(News::class, 'cid', $this->getKeyName());
    }
    // 该分类下的miscellaneous
    public function miscellaneous()
    {
        return $this->hasMany(Miscellaneous::class, 'cid', $this->getKeyName());
    }
    /**
     * 该分类的父分类
     */
    public function parent()
    {
        return $this->hasOne(get_class($this), $this->getKeyName(), 'pid');
    }

    public static function GetAllMenuTree()
    {
        $data = self::query()
            ->select('id','cate_name','pid','sort','url')->orderBy('sort','asc')
            ->get()->toArray();
        $tree = [];
//        $tree = self::list_level($data,$pid=0,$level=0);

        self::ListToTree($data,'id','pid','children',0,$tree);

        return $tree;

    }

    public static function ListToTree($list, $primaryKey='id', $parentKey = 'pid', $childStr = 'children', $root = 0 ,array &$tree)
    {

        if (is_array($list)) {

            //创建基于主键的数组引用
            $refer = array();

            foreach ($list as $key => $data) {
                $refer[$data[$primaryKey]] = &$list[$key];
            }

            foreach ($list as $key => $data) {

                //判断是否存在parent
                $parantId = $data[$parentKey];
                if ($root == $parantId) {

                    $tree[] = &$list[$key];


                } else {
                    if (isset($refer[$parantId])) {
                        $parent = &$refer[$parantId];
                        $parent[$childStr][] = &$list[$key];
                    }

                }
            }
        }

        return $tree;
    }
    public static function list_level($data,$pid,$level){

        static $array = array();

        foreach ($data as $k => $v) {

            if($pid == $v['pid']){

                $v['level'] = $level;

                $array[] = $v;

                self::list_level($data,$v['id'],$level+1);
            }
        }
        return $array;
    }
    public static function arr2tree($list, $id = 'id', $pid = 'pid', $son = 'sub')
    {
        list($tree, $map) = [[], []];
        foreach ($list as $item) {
            $map[$item[$id]] = $item;
        }

        foreach ($list as $item) {
            if (isset($item[$pid]) && isset($map[$item[$pid]])) {
                $map[$item[$pid]][$son][] = &$map[$item[$id]];
            } else {
                $tree[] = &$map[$item[$id]];
            }
        }
        unset($map);
        return $tree;
    }

    public function children() {
        return $this->hasMany(get_class($this), 'pid' ,'id');
    }

    public function allChildren() {
        return $this->children()->with( 'allChildren' );
    }
    public function getList($params){
        $query = self::newQuery();

        if(isset($params['cate_name']) && !empty($params['cate_name']))  $query->where('cate_name','like',$params['cate_name'].'%');
        if(isset($params['pid']) && !empty($params['pid']))  $query->where('pid','=',$params['pid']);
        if(isset($params['page']) && !empty($params['page'])){
            return $query->paginate($params['pageSize'], ['*'],  'page',$params['page']);

        }else{
            return $query->take(5)->get();
        }

    }
}
