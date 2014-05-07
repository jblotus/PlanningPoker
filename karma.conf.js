// Karma configuration
// Generated on Sun Apr 27 2014 16:58:35 GMT+0000 (UTC)

module.exports = function(config) {
  config.set({

    // base path that will be used to resolve all patterns (eg. files, exclude)
    basePath: '',


    // frameworks to use
    // available frameworks: https://npmjs.org/browse/keyword/karma-adapter
    frameworks: ['jasmine'],


    // list of files / patterns to load in the browser
    files: [
      './bower_components/jquery/dist/jquery.js',
      './bower_components/underscore/underscore.js',
      './bower_components/backbone/backbone.js',
      './bower_components/handlebars/handlebars.js',
      './bower_components/bootstrap/dist/js/bootstrap.js',      
      'webroot/assets/javascript/testshim.js',
      'webroot/assets/javascript/src/modules/*.js',
      'webroot/assets/javascript/src/app.js',
      'webroot/assets/javascript/tests/modules/*.spec.js',
      'webroot/assets/javascript/tests/*.spec.js',
    ],


    // list of files to exclude
    exclude: [
      
    ],


    // preprocess matching files before serving them to the browser
    // available preprocessors: https://npmjs.org/browse/keyword/karma-preprocessor
    preprocessors: {
      'webroot/assets/javascript/src/modules/*.js' : ['coverage'],
      'webroot/assets/javascript/src/app.js' : ['coverage']
    },


    // test results reporter to use
    // possible values: 'dots', 'progress'
    // available reporters: https://npmjs.org/browse/keyword/karma-reporter
    reporters: ['spec', 'coverage'],
    
    coverageReporter: {
      type: 'lcov', // lcov or lcovonly are required for generating lcov.info files
      dir: 'coverage/'
    },


    // web server port
    port: 9876,


    // enable / disable colors in the output (reporters and logs)
    colors: true,


    // level of logging
    // possible values: config.LOG_DISABLE || config.LOG_ERROR || config.LOG_WARN || config.LOG_INFO || config.LOG_DEBUG
    logLevel: config.LOG_INFO,


    // enable / disable watching file and executing tests whenever any file changes
    autoWatch: true,


    // start these browsers
    // available browser launchers: https://npmjs.org/browse/keyword/karma-launcher
    browsers: ['PhantomJS'],


    // Continuous Integration mode
    // if true, Karma captures browsers, runs the tests and exits
    singleRun: false
  });
};
