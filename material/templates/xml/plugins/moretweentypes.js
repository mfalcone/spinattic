/* krpano 1.16.6 moretweentypes plugin (build 2013-08-09) */
var krpanoplugin=function(){function d(a,c,b){return a<1/2.75?b*7.5625*a*a+c:a<2/2.75?b*(7.5625*(a-=1.5/2.75)*a+0.75)+c:a<2.5/2.75?b*(7.5625*(a-=2.25/2.75)*a+0.9375)+c:b*(7.5625*(a-=2.625/2.75)*a+0.984375)+c}function e(a,c,b){return(1>(a/=0.5)?b/2*a*a:-b/2*(--a*(a-2)-1))+c}function f(a,c,b){return 0.5>a?-(b/2)*(2*a)*(2*a-2)+c:b/2*(2*a-1)*(2*a-1)+(c+b/2)}function g(a,c,b){return 1>(a/=0.5)?b/2*a*a*a+c:b/2*((a-=2)*a*a+2)+c}function h(a,c,b){0.5>a?(a*=2,c=b/2*((a-=1)*a*a+1)+c):c=b/2*(2*a-1)*(2*a-1)*
(2*a-1)+(c+b/2);return c}function j(a,c,b){return 1>(a/=0.5)?b/2*a*a*a*a+c:-b/2*((a-=2)*a*a*a-2)+c}function k(a,c,b){0.5>a?(a*=2,c=-(b/2)*((a-=1)*a*a*a-1)+c):c=b/2*(2*a-1)*(2*a-1)*(2*a-1)*(2*a-1)+(c+b/2);return c}function l(a,c,b){return 1>(a/=0.5)?b/2*a*a*a*a*a+c:b/2*((a-=2)*a*a*a*a+2)+c}function m(a,c,b){0.5>a?(a*=2,c=b/2*((a-=1)*a*a*a*a+1)+c):c=b/2*(2*a-1)*(2*a-1)*(2*a-1)*(2*a-1)*(2*a-1)+(c+b/2);return c}function n(a,c,b){return-b/2*(Math.cos(Math.PI*a)-1)+c}function p(a,c,b){return 0.5>a?b/2*
Math.sin(2*a*(Math.PI/2))+c:-(b/2)*Math.cos((2*a-1)*(Math.PI/2))+b/2+(c+b/2)}function q(a,c,b){return 0.5>a?1==2*a?c+b/2:1.001*(b/2)*(-Math.pow(2,-10*2*a)+1)+c:0==2*a-1?c+b/2:b/2*Math.pow(2,10*(2*a-1-1))+(c+b/2)-0.001*(b/2)}function r(a,c,b){return 0==a?c:1==a?c+b:1>a/2?b/2*Math.pow(2,10*(a-1))+c-5E-4*b:1.0005*(b/2)*(-Math.pow(2,-10*--a)+2)+c}function s(a,c,b){return 1>(a/=0.5)?-b/2*(Math.sqrt(1-a*a)-1)+c:b/2*(Math.sqrt(1-(a-=2)*a)+1)+c}function t(a,c,b){0.5>a?(a*=2,c=b/2*Math.sqrt(1-(a-=1)*a)+c):
c=-(b/2)*(Math.sqrt(1-(2*a-1)*(2*a-1))-1)+(c+b/2);return c}function u(a,c,b){return 0.5>a?0.5*(b-d(1-2*a,0,b)+0)+c:0.5*d(2*a-1,0,b)+0.5*b+c}function v(a,c,b){return 0.5>a?d(2*a,c,b/2):b/2-d(1-(2*a-1),0,b/2)+(c+b/2)}this.registerplugin=function(a){a.tweentypes.easeinoutquad=e;a.tweentypes.easeoutinquad=f;a.tweentypes.easeinoutcubic=g;a.tweentypes.easeoutincubic=h;a.tweentypes.easeinoutquart=j;a.tweentypes.easeoutinquart=k;a.tweentypes.easeinoutquint=l;a.tweentypes.easeoutinquint=m;a.tweentypes.easeinoutsine=
n;a.tweentypes.easeoutinsine=p;a.tweentypes.easeoutinexpo=q;a.tweentypes.easeinoutexpo=r;a.tweentypes.easeinoutcirc=s;a.tweentypes.easeoutincirc=t;a.tweentypes.easeinoutbounce=u;a.tweentypes.easeoutinbounce=v};this.unloadplugin=function(){}};
