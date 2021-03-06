module.exports = function(grunt) {
  
  grunt.initConfig({
    
    // Task configuration
    concat: {
     options: {
        separator: ';',
      },
      js: {
        src: [
          './bower_components/jquery/dist/jquery.js',
          './bower_components/underscore/underscore.js',
          './bower_components/backbone/backbone.js',
          './bower_components/handlebars/handlebars.js',
          './bower_components/bootstrap/dist/js/bootstrap.js',
          './bower_components/pusher/dist/pusher.js',
          './webroot/assets/javascript/src/modules/*.js',
          './webroot/assets/javascript/src/*.js'
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
          './webroot/assets/javascript/src/*.js'
        ],   
        tasks: ['concat:js','uglify:js', 'karma:unit:run'],     //tasks to run
        options: {
          livereload: true                        //reloads the browser
        }
      }, 
      less: {
        files: ['./webroot/assets/stylesheets/*.less'],  //watched files
        tasks: ['less'],                          //tasks to run
        options: {
          livereload: true
        }
      },
      
      tasks: {
        files: ['src/*.php', 'tests/src/*.php', './webroot/assets/javascript/tests/*.js'],
        tasks: ['phpunit', 'karma:unit:run']
      }
    },
    
    karma: {
      unit: {
        configFile: 'karma.conf.js',
        background: true
      }
    }
  });
   
  grunt.loadNpmTasks('grunt-contrib-concat');
  grunt.loadNpmTasks('grunt-contrib-less');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-phpunit');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-karma');
  
  grunt.registerTask('default', ['watch']);
};