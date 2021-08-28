<?php
return [
    'enable' => function ($app) {
        $util = $app['db']->getUtility();

        if( !$util->tableExists('@docs_post') ){
            $util->createTable('@docs_post' , function($table){
                $table->addColumn('id' , 'integer' , ['autoincrement' => true , 'unsigned' => true , 'length' => 10]);
                $table->addColumn('user_id' , 'integer' , ['notnull' => false]);
                $table->addColumn('category_id' , 'integer');
                $table->addColumn('title' , 'string');
                $table->addColumn('slug' , 'string');
                $table->addColumn('status' , 'integer');
                $table->addColumn('date' , 'datetime');
                $table->addColumn('modified' , 'datetime' , ['notnull' => false]);
                $table->addColumn('content' , 'text' , ['notnull' => false]);
                $table->addColumn('priority' , 'integer' , ['default' => 999]);
                $table->addColumn('data' , 'json' , ['notnull' => false]);
                $table->setPrimaryKey(['id']);
                $table->addIndex(['title'] , '@DOCS_POST_TITLE');
                $table->addIndex(['slug'] , '@DOCS_POST_SLUG');
            });
        }

        if( !$util->tableExists('@docs_category') ){
            $util->createTable('@docs_category' , function($table){
                $table->addColumn('id' , 'integer' , ['autoincrement' => true , 'unsigned' => true , 'length' => 10]);
                $table->addColumn('title' , 'string');
                $table->addColumn('slug' , 'string');
                $table->addColumn('status' , 'integer');
                $table->addColumn('priority' , 'integer' , ['default' => 999]);
                $table->addColumn('roles' , 'simple_array' , ['notnull' => false]);
                $table->addColumn('data' , 'json' , ['notnull' => false]);
                $table->setPrimaryKey(['id']);
                $table->addIndex(['title'] , '@DOCS_CATEGORY_TITLE');
                $table->addIndex(['slug'] , '@DOCS_CATEGORY_SLUG');
            });
        }
    },
    'disable' => function ($app) {
        $util = $app['db']->getUtility();
    },
    'updates' => []
];
