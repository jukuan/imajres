const gulp = require('gulp');
const image = require('gulp-image');
 
gulp.task('image', function () {
  gulp.src('./modules/**/*')
    .pipe(image({
      pngquant: true,
      optipng: false,
      zopflipng: true,
      jpegRecompress: false,
      mozjpeg: true,
      guetzli: false,
      gifsicle: true,
      svgo: true,
      concurrent: 10,
    }))
    .pipe(gulp.dest('./dest'));
});
 
gulp.task('default', ['image']);

