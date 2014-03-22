module.exports = function(grunt) {
  
  grunt.initConfig({
    
    // Task configuration
    concat: {
      
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
      
    },
    phpunit: {
      
    }, 
    watch: {
      
    }
  });
  
  // Plugin loading
  grunt.loadNpmTasks('grunt-contrib-less');  
  
  // Task definition
};