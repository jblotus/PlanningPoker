module.exports = function(grunt) {
  
  grunt.initConfig({
    
    // Task configuration
    concat: {
     options: {
        separator: ';',
      },
      js: {
        src: [
          './bower_components/jquery/jquery.js',
          './bower_components/bootstrap/dist/js/bootstrap.js',
          './webroot/assets/javascript/app.js'
        ],
        dest: './webroot/assets/javascript/main.js',
      }
    },
    less: {
      development: {
        options: {
          compress: true,  //minifying the result
        },
        files: {
          //compiling frontend.less into frontend.css
          "./webroot/assets/stylesheets/main.css"  :"./webroot/assets/stylesheets/main.less",
        }
      }
    },
    uglify: {
      options: {
        mangle: false  // Use if you want the names of your functions and variables unchanged
      },
      js: {
        files: {
          './webroot/assets/javascript/main.js': './webroot/assets/javascript/main.js',
        }
      }
    },
    phpunit: {
      classes: {
        dir: 'tests/src'   //location of the tests
      },
      options: {
        bin: 'vendor/bin/phpunit',
        colors: true,
        bootstrap: './tests/bootstrap.php'
      }
    },
    watch: {
      js: {
        files: [
          //watched files
          './bower_components/jquery/jquery.js',
          './bower_components/bootstrap/dist/js/bootstrap.js',
          './webroot/assets/javascript/app.js'
        ],   
        tasks: ['concat:js','uglify:js'],     //tasks to run
        options: {
          livereload: true                        //reloads the browser
        }
      }, 
      less: {
        files: ['./webroot/assets/stylesheets/*.less'],  //watched files
        tasks: ['less'],                          //tasks to run
        options: {
          livereload: true                        //reloads the browser
        }
      },
      tests: {
        files: ['src/*.php', 'tests/src/*.php'],  //the task will run only when you save files in this location
        tasks: ['phpunit']
      }
    }
  });
   
  grunt.loadNpmTasks('grunt-contrib-concat');
  grunt.loadNpmTasks('grunt-contrib-less');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-phpunit');
  grunt.loadNpmTasks('grunt-contrib-watch');
  
  grunt.registerTask('default', ['watch']);
};