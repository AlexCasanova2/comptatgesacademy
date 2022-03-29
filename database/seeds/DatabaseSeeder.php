<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	
		

		
   
    
		// Schema::create('Stand', function (Blueprint $table) {
            // $table->increments('id');
			// $table->string('nom');
			// $table->softDeletes();
            // $table->timestamps();
        // });
// 		
// 		    
		// Schema::create('Comptatge', function (Blueprint $table) {
            // $table->increments('id');
			// $table->string('id_stand');
			// $table->integer('comptatge');
			// $table->softDeletes();
            // $table->timestamps();
        // });
// 		
		// Schema::create('albaranes_productos', function (Blueprint $table) {
            // $table->increments('id');
			// $table->integer('id_producto');
			// $table->integer('id_albaran');
			// $table->integer('cantidad');
			// $table->softDeletes();
            // $table->timestamps();
        // });
// 		
		
		
 	/*	
		Schema::create('imagenes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('producto');
			$table->string('path');
			$table->string('name');
			$table->softDeletes();
            $table->timestamps();
        });


		Schema::create('ficheros', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('producto');
			$table->string('path');
			$table->string('name');
			$table->softDeletes();
            $table->timestamps();
        });
		
		Schema::create('zonas', function (Blueprint $table) {
            $table->increments('id');
			$table->string('nombre');
			$table->string('mapa');
			$table->softDeletes();
            $table->timestamps();
        });
		
		Schema::create('eventos', function (Blueprint $table) {
            $table->increments('id');
			$table->string('nombre');
			$table->softDeletes();
            $table->timestamps();
        });
		
		Schema::create('historial', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('producto');
			$table->integer('estado');
			$table->softDeletes();
            $table->timestamps();
        });	
		Schema::create('tags', function (Blueprint $table) {
            $table->increments('id');
			$table->string('tag');
			$table->integer('producto');
			$table->softDeletes();
            $table->timestamps();
        });		
		
		Schema::create('categorias', function (Blueprint $table) {
            $table->increments('id');
			$table->string('nombre');
			$table->softDeletes();
            $table->timestamps();
        });	
		
    	/*	
    	Schema::create('visualconfig', function (Blueprint $table) {
            $table->increments('id');
            $table->string('image');
			$table->string('title');
            $table->string('subtitle');
			$table->string('link');
			$table->string('type');
			$table->integer('visible');
			$table->softDeletes();
            $table->timestamps();
        });
		*/
	/*	Schema::create('visitas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user');
			$table->string('note');
            $table->dateTime('visita_date');
			$table->softDeletes();
            $table->timestamps();
        });*/
		
	/*	Schema::create('descargas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user');
			$table->string('title');
			$table->string('subtitle');
			$table->string('link');
			$table->string('image');
			$table->string('file');
			$table->softDeletes();
            $table->timestamps();
        });*/
		
    	/*
		Schema::create('config', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug');
            $table->string('value');
            $table->timestamps();
        });*/
		
		

 		
       
	    // // $this->call(UsersTableSeeder::class);
         // echo "\n\n CREACION DE ROLES\n";
        // echo " -----------------\n\n";
        // echo " > Rol user..";
        // $rol_admin = Sentinel::getRoleRepository()->createModel()->create([
          // 'name' => 'User',
          // 'slug' => 'user',
        // ]);
        // echo ". creado!\n";
     
	 
 		   	// $uss= 'useradmin';
   	        // //Creamos los usuarios, asignamos roles y activamos
		    // echo "\n\n CREACION DE USUARIOS\n";
		    // echo " -----------------\n\n";
// 		
			// $rol_admin = Sentinel::findRoleBySlug('admin');
		    // $user = Sentinel::create([
		      // 'email'    => $uss,
		      // 'password' => 'fcb2020',
		      // 'first_name' => 'Admin',
		      // 'last_name' => 'FCB',
		    // ]);
		    // echo " > Usuario $user->email ... creado!\n";
		    // $rol_admin->users()->attach($user);
		    // echo " > Rol admin asignado!\n";
		    // $act = Activation::create($user);
		    // Activation::complete($user,$act->code);
		    // echo " > Activación $user->email completada!\n\n";  
		    
		    
 		   	$uss= 'oscar@tandemprojects.cat';
   	        //Creamos los usuarios, asignamos roles y activamos
		    echo "\n\n CREACION DE USUARIOS\n";
		    echo " -----------------\n\n";
		
			$rol_admin = Sentinel::findRoleBySlug('admin');
		    $user = Sentinel::create([
		      'email'    => $uss,
		      'password' => 'tandemfcb2020',
		      'first_name' => 'Oscar',
		      'last_name' => 'Tandem',
		    ]);
		    echo " > Usuario $user->email ... creado!\n";
		    $rol_admin->users()->attach($user);
		    echo " > Rol admin asignado!\n";
		    $act = Activation::create($user);
		    Activation::complete($user,$act->code);
		    echo " > Activación $user->email completada!\n\n"; 
       
		    
		    $uss= 'muntsa@tandemprojects.cat';
		    //Creamos los usuarios, asignamos roles y activamos
		    echo "\n\n CREACION DE USUARIOS\n";
		    echo " -----------------\n\n";
		    
		    $rol_admin = Sentinel::findRoleBySlug('admin');
		    $user = Sentinel::create([
		        'email'    => $uss,
		        'password' => 'tandemfcb2020',
		        'first_name' => 'Muntsa',
		        'last_name' => 'Tandem',
		    ]);
		    echo " > Usuario $user->email ... creado!\n";
		    $rol_admin->users()->attach($user);
		    echo " > Rol admin asignado!\n";
		    $act = Activation::create($user);
		    Activation::complete($user,$act->code);
		    echo " > Activación $user->email completada!\n\n"; 
		    
       // for ($i=0; $i < 20; $i++) { 
//            
// 		   
		   	// $uss= 'user'.$i;
   	        // //Creamos los usuarios, asignamos roles y activamos
		    // echo "\n\n CREACION DE USUARIOS\n";
		    // echo " -----------------\n\n";
// 		
			// $rol_admin = Sentinel::findRoleBySlug('user');
		    // $user = Sentinel::create([
		      // 'email'    => $uss,
		      // 'password' => 'fcb2020',
		      // 'first_name' => 'User '.$i,
		      // 'last_name' => 'FCB',
		    // ]);
		    // echo " > Usuario $user->email ... creado!\n";
		    // $rol_admin->users()->attach($user);
		    // echo " > Rol admin asignado!\n";
		    // $act = Activation::create($user);
		    // Activation::complete($user,$act->code);
		    // echo " > Activación $user->email completada!\n\n";
// 		    
// 		   
       // }

		
		
		
		
    }
    
    
}