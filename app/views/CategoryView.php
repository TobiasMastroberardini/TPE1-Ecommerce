<?php
class CategoryView{
    function showCreateCategory(){
        require_once 'template/createCategory.phtml';
    }

    function showCategoryList($categorias){
        require_once 'template/categoryList.phtml';
    }
}