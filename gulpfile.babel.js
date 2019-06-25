import gulp from 'gulp';
import concat from 'gulp-concat';
import sourcemaps from 'gulp-sourcemaps';
import uglify from 'gulp-uglify';

const paths = {
	scripts: {
		src: [
			'node_modules/lazysizes/plugins/respimg/ls.respimg.js',
			'node_modules/lazysizes/plugins/unveilhooks/ls.unveilhooks.js',
			'node_modules/lazysizes/plugins/object-fit/ls.object-fit.js',
			'node_modules/lazysizes/plugins/parent-fit/ls.parent-fit.js',
			'node_modules/lazysizes/lazysizes.js',
		],
		dest: 'assets/dist/scripts/',
	}
};

export function scripts() {
	return gulp.src( paths.scripts.src, { sourcemaps: true } )
		.pipe( uglify() )
		.pipe( concat( 'lazysizes.min.js' ) )
		.pipe( gulp.dest( paths.scripts.dest ) );
}

export default scripts;
