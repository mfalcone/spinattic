! function(a) {
    "function" == typeof define ? define(a) : "function" == typeof YUI ? YUI.add("es5", a) : a()
}(function() {
    function a() {}

    function b(a) {
        return a = +a, a !== a ? a = 0 : 0 !== a && a !== 1 / 0 && a !== -(1 / 0) && (a = (a > 0 || -1) * Math.floor(Math.abs(a))), a
    }

    function c(a) {
        var b = typeof a;
        return null === a || "undefined" === b || "boolean" === b || "number" === b || "string" === b
    }

    function d(a) {
        var b, d, e;
        if (c(a)) return a;
        if (d = a.valueOf, "function" == typeof d && (b = d.call(a), c(b))) return b;
        if (e = a.toString, "function" == typeof e && (b = e.call(a), c(b))) return b;
        throw new TypeError
    }
    Function.prototype.bind || (Function.prototype.bind = function(b) {
        var c = this;
        if ("function" != typeof c) throw new TypeError("Function.prototype.bind called on incompatible " + c);
        for (var d = m.call(arguments, 1), e = function() {
                if (this instanceof i) {
                    var a = c.apply(this, d.concat(m.call(arguments)));
                    return Object(a) === a ? a : this
                }
                return c.apply(b, d.concat(m.call(arguments)))
            }, f = Math.max(0, c.length - d.length), g = [], h = 0; f > h; h++) g.push("$" + h);
        var i = Function("binder", "return function(" + g.join(",") + "){return binder.apply(this,arguments)}")(e);
        return c.prototype && (a.prototype = c.prototype, i.prototype = new a, a.prototype = null), i
    });
    var e, f, g, h, i, j = Function.prototype.call,
        k = Array.prototype,
        l = Object.prototype,
        m = k.slice,
        n = j.bind(l.toString),
        o = j.bind(l.hasOwnProperty);
    if ((i = o(l, "__defineGetter__")) && (e = j.bind(l.__defineGetter__), f = j.bind(l.__defineSetter__), g = j.bind(l.__lookupGetter__), h = j.bind(l.__lookupSetter__)), 2 != [1, 2].splice(0).length) {
        var p = Array.prototype.splice,
            q = Array.prototype.push,
            r = Array.prototype.unshift;
        Array.prototype.splice = function() {
            function a(a) {
                for (var b = []; a--;) b.unshift(a);
                return b
            }
            var b, c = [];
            return c.splice.bind(c, 0, 0).apply(null, a(20)), c.splice.bind(c, 0, 0).apply(null, a(26)), b = c.length, c.splice(5, 0, "XXX"), b + 1 == c.length ? !0 : void 0
        }() ? function(a, b) {
            return arguments.length ? p.apply(this, [void 0 === a ? 0 : a, void 0 === b ? this.length - a : b].concat(m.call(arguments, 2))) : []
        } : function(a, b) {
            var c, d = m.call(arguments, 2),
                e = d.length;
            if (!arguments.length) return [];
            if (void 0 === a && (a = 0), void 0 === b && (b = this.length - a), e > 0) {
                if (0 >= b) {
                    if (a == this.length) return q.apply(this, d), [];
                    if (0 == a) return r.apply(this, d), []
                }
                return c = m.call(this, a, a + b), d.push.apply(d, m.call(this, a + b, this.length)), d.unshift.apply(d, m.call(this, 0, a)), d.unshift(0, this.length), p.apply(this, d), c
            }
            return p.call(this, a, b)
        }
    }
    if (1 != [].unshift(0)) {
        var r = Array.prototype.unshift;
        Array.prototype.unshift = function() {
            return r.apply(this, arguments), this.length
        }
    }
    Array.isArray || (Array.isArray = function(a) {
        return "[object Array]" == n(a)
    });
    var s = Object("a"),
        t = "a" != s[0] || !(0 in s),
        u = !0;
    if (Array.prototype.forEach && Array.prototype.forEach.call("foo", function(a, b, c) {
            "object" != typeof c && (u = !1)
        }), Array.prototype.forEach && u || (Array.prototype.forEach = function(a) {
            var b = I(this),
                c = t && "[object String]" == n(this) ? this.split("") : b,
                d = arguments[1],
                e = -1,
                f = c.length >>> 0;
            if ("[object Function]" != n(a)) throw new TypeError;
            for (; ++e < f;) e in c && a.call(d, c[e], e, b)
        }), Array.prototype.map || (Array.prototype.map = function(a) {
            var b = I(this),
                c = t && "[object String]" == n(this) ? this.split("") : b,
                d = c.length >>> 0,
                e = Array(d),
                f = arguments[1];
            if ("[object Function]" != n(a)) throw new TypeError(a + " is not a function");
            for (var g = 0; d > g; g++) g in c && (e[g] = a.call(f, c[g], g, b));
            return e
        }), Array.prototype.filter || (Array.prototype.filter = function(a) {
            var b, c = I(this),
                d = t && "[object String]" == n(this) ? this.split("") : c,
                e = d.length >>> 0,
                f = [],
                g = arguments[1];
            if ("[object Function]" != n(a)) throw new TypeError(a + " is not a function");
            for (var h = 0; e > h; h++) h in d && (b = d[h], a.call(g, b, h, c) && f.push(b));
            return f
        }), Array.prototype.every || (Array.prototype.every = function(a) {
            var b = I(this),
                c = t && "[object String]" == n(this) ? this.split("") : b,
                d = c.length >>> 0,
                e = arguments[1];
            if ("[object Function]" != n(a)) throw new TypeError(a + " is not a function");
            for (var f = 0; d > f; f++)
                if (f in c && !a.call(e, c[f], f, b)) return !1;
            return !0
        }), Array.prototype.some || (Array.prototype.some = function(a) {
            var b = I(this),
                c = t && "[object String]" == n(this) ? this.split("") : b,
                d = c.length >>> 0,
                e = arguments[1];
            if ("[object Function]" != n(a)) throw new TypeError(a + " is not a function");
            for (var f = 0; d > f; f++)
                if (f in c && a.call(e, c[f], f, b)) return !0;
            return !1
        }), Array.prototype.reduce || (Array.prototype.reduce = function(a) {
            var b = I(this),
                c = t && "[object String]" == n(this) ? this.split("") : b,
                d = c.length >>> 0;
            if ("[object Function]" != n(a)) throw new TypeError(a + " is not a function");
            if (!d && 1 == arguments.length) throw new TypeError("reduce of empty array with no initial value");
            var e, f = 0;
            if (arguments.length >= 2) e = arguments[1];
            else
                for (;;) {
                    if (f in c) {
                        e = c[f++];
                        break
                    }
                    if (++f >= d) throw new TypeError("reduce of empty array with no initial value")
                }
            for (; d > f; f++) f in c && (e = a.call(void 0, e, c[f], f, b));
            return e
        }), Array.prototype.reduceRight || (Array.prototype.reduceRight = function(a) {
            var b = I(this),
                c = t && "[object String]" == n(this) ? this.split("") : b,
                d = c.length >>> 0;
            if ("[object Function]" != n(a)) throw new TypeError(a + " is not a function");
            if (!d && 1 == arguments.length) throw new TypeError("reduceRight of empty array with no initial value");
            var e, f = d - 1;
            if (arguments.length >= 2) e = arguments[1];
            else
                for (;;) {
                    if (f in c) {
                        e = c[f--];
                        break
                    }
                    if (--f < 0) throw new TypeError("reduceRight of empty array with no initial value")
                }
            if (0 > f) return e;
            do f in this && (e = a.call(void 0, e, c[f], f, b)); while (f--);
            return e
        }), Array.prototype.indexOf && -1 == [0, 1].indexOf(1, 2) || (Array.prototype.indexOf = function(a) {
            var c = t && "[object String]" == n(this) ? this.split("") : I(this),
                d = c.length >>> 0;
            if (!d) return -1;
            var e = 0;
            for (arguments.length > 1 && (e = b(arguments[1])), e = e >= 0 ? e : Math.max(0, d + e); d > e; e++)
                if (e in c && c[e] === a) return e;
            return -1
        }), Array.prototype.lastIndexOf && -1 == [0, 1].lastIndexOf(0, -3) || (Array.prototype.lastIndexOf = function(a) {
            var c = t && "[object String]" == n(this) ? this.split("") : I(this),
                d = c.length >>> 0;
            if (!d) return -1;
            var e = d - 1;
            for (arguments.length > 1 && (e = Math.min(e, b(arguments[1]))), e = e >= 0 ? e : d - Math.abs(e); e >= 0; e--)
                if (e in c && a === c[e]) return e;
            return -1
        }), !Object.keys) {
        var v = !0,
            w = ["toString", "toLocaleString", "valueOf", "hasOwnProperty", "isPrototypeOf", "propertyIsEnumerable", "constructor"],
            x = w.length;
        for (var y in {
                toString: null
            }) v = !1;
        Object.keys = function J(a) {
            if ("object" != typeof a && "function" != typeof a || null === a) throw new TypeError("Object.keys called on a non-object");
            var J = [];
            for (var b in a) o(a, b) && J.push(b);
            if (v)
                for (var c = 0, d = x; d > c; c++) {
                    var e = w[c];
                    o(a, e) && J.push(e)
                }
            return J
        }
    }
    var z = -621987552e5,
        A = "-000001";
    Date.prototype.toISOString && -1 !== new Date(z).toISOString().indexOf(A) || (Date.prototype.toISOString = function() {
        var a, b, c, d, e;
        if (!isFinite(this)) throw new RangeError("Date.prototype.toISOString called on non-finite value.");
        for (d = this.getUTCFullYear(), e = this.getUTCMonth(), d += Math.floor(e / 12), e = (e % 12 + 12) % 12, a = [e + 1, this.getUTCDate(), this.getUTCHours(), this.getUTCMinutes(), this.getUTCSeconds()], d = (0 > d ? "-" : d > 9999 ? "+" : "") + ("00000" + Math.abs(d)).slice(d >= 0 && 9999 >= d ? -4 : -6), b = a.length; b--;) c = a[b], 10 > c && (a[b] = "0" + c);
        return d + "-" + a.slice(0, 2).join("-") + "T" + a.slice(2).join(":") + "." + ("000" + this.getUTCMilliseconds()).slice(-3) + "Z"
    });
    var B = !1;
    try {
        B = Date.prototype.toJSON && null === new Date(0 / 0).toJSON() && -1 !== new Date(z).toJSON().indexOf(A) && Date.prototype.toJSON.call({
            toISOString: function() {
                return !0
            }
        })
    } catch (C) {}
    B || (Date.prototype.toJSON = function() {
        var a, b = Object(this),
            c = d(b);
        if ("number" == typeof c && !isFinite(c)) return null;
        if (a = b.toISOString, "function" != typeof a) throw new TypeError("toISOString property is not callable");
        return a.call(b)
    }), Date = function(a) {
        function b(c, d, e, f, g, h, i) {
            var j = arguments.length;
            if (this instanceof a) {
                var k = 1 == j && String(c) === c ? new a(b.parse(c)) : j >= 7 ? new a(c, d, e, f, g, h, i) : j >= 6 ? new a(c, d, e, f, g, h) : j >= 5 ? new a(c, d, e, f, g) : j >= 4 ? new a(c, d, e, f) : j >= 3 ? new a(c, d, e) : j >= 2 ? new a(c, d) : j >= 1 ? new a(c) : new a;
                return k.constructor = b, k
            }
            return a.apply(this, arguments)
        }

        function c(a, b) {
            var c = b > 1 ? 1 : 0;
            return f[b] + Math.floor((a - 1969 + c) / 4) - Math.floor((a - 1901 + c) / 100) + Math.floor((a - 1601 + c) / 400) + 365 * (a - 1970)
        }

        function d(b) {
            return Number(new a(1970, 0, 1, 0, 0, 0, b))
        }
        var e = new RegExp("^(\\d{4}|[+-]\\d{6})(?:-(\\d{2})(?:-(\\d{2})(?:T(\\d{2}):(\\d{2})(?::(\\d{2})(?:(\\.\\d{1,}))?)?(Z|(?:([-+])(\\d{2}):(\\d{2})))?)?)?)?$"),
            f = [0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334, 365];
        for (var g in a) b[g] = a[g];
        return b.now = a.now, b.UTC = a.UTC, b.prototype = a.prototype, b.prototype.constructor = b, b.parse = function(b) {
            var f = e.exec(b);
            if (f) {
                var g, h = Number(f[1]),
                    i = Number(f[2] || 1) - 1,
                    j = Number(f[3] || 1) - 1,
                    k = Number(f[4] || 0),
                    l = Number(f[5] || 0),
                    m = Number(f[6] || 0),
                    n = Math.floor(1e3 * Number(f[7] || 0)),
                    o = Boolean(f[4] && !f[8]),
                    p = "-" === f[9] ? 1 : -1,
                    q = Number(f[10] || 0),
                    r = Number(f[11] || 0);
                return (l > 0 || m > 0 || n > 0 ? 24 : 25) > k && 60 > l && 60 > m && 1e3 > n && i > -1 && 12 > i && 24 > q && 60 > r && j > -1 && j < c(h, i + 1) - c(h, i) && (g = 60 * (24 * (c(h, i) + j) + k + q * p), g = 1e3 * (60 * (g + l + r * p) + m) + n, o && (g = d(g)), g >= -864e13 && 864e13 >= g) ? g : 0 / 0
            }
            return a.parse.apply(this, arguments)
        }, b
    }(Date), Date.now || (Date.now = function() {
        return (new Date).getTime()
    }), Number.prototype.toFixed && "0.000" === 8e-5.toFixed(3) && "0" !== .9. toFixed(0) && "1.25" === 1.255.toFixed(2) && "1000000000000000128" === 0xde0b6b3a7640080.toFixed(0) || ! function() {
        function a(a, b) {
            for (var c = -1; ++c < g;) b += a * h[c], h[c] = b % f, b = Math.floor(b / f)
        }

        function b(a) {
            for (var b = g, c = 0; --b >= 0;) c += h[b], h[b] = Math.floor(c / a), c = c % a * f
        }

        function c() {
            for (var a = g, b = ""; --a >= 0;)
                if ("" !== b || 0 === a || 0 !== h[a]) {
                    var c = String(h[a]);
                    "" === b ? b = c : b += "0000000".slice(0, 7 - c.length) + c
                }
            return b
        }

        function d(a, b, c) {
            return 0 === b ? c : b % 2 === 1 ? d(a, b - 1, c * a) : d(a * a, b / 2, c)
        }

        function e(a) {
            for (var b = 0; a >= 4096;) b += 12, a /= 4096;
            for (; a >= 2;) b += 1, a /= 2;
            return b
        }
        var f, g, h;
        f = 1e7, g = 6, h = [0, 0, 0, 0, 0, 0], Number.prototype.toFixed = function(f) {
            var g, h, i, j, k, l, m, n;
            if (g = Number(f), g = g !== g ? 0 : Math.floor(g), 0 > g || g > 20) throw new RangeError("Number.toFixed called with invalid number of decimals");
            if (h = Number(this), h !== h) return "NaN";
            if (-1e21 >= h || h >= 1e21) return String(h);
            if (i = "", 0 > h && (i = "-", h = -h), j = "0", h > 1e-21)
                if (k = e(h * d(2, 69, 1)) - 69, l = 0 > k ? h * d(2, -k, 1) : h / d(2, k, 1), l *= 4503599627370496, k = 52 - k, k > 0) {
                    for (a(0, l), m = g; m >= 7;) a(1e7, 0), m -= 7;
                    for (a(d(10, m, 1), 0), m = k - 1; m >= 23;) b(1 << 23), m -= 23;
                    b(1 << m), a(1, 1), b(2), j = c()
                } else a(0, l), a(1 << -k, 0), j = c() + "0.00000000000000000000".slice(2, 2 + g);
            return g > 0 ? (n = j.length, j = g >= n ? i + "0.0000000000000000000".slice(0, g - n + 2) + j : i + j.slice(0, n - g) + "." + j.slice(n - g)) : j = i + j, j
        }
    }();
    var D = String.prototype.split;
    if (2 !== "ab".split(/(?:ab)*/).length || 4 !== ".".split(/(.?)(.?)/).length || "t" === "tesst".split(/(s)*/)[1] || "".split(/.?/).length || ".".split(/()()/).length > 1 ? ! function() {
            var a = void 0 === /()??/.exec("")[1];
            String.prototype.split = function(b, c) {
                var d = this;
                if (void 0 === b && 0 === c) return [];
                if ("[object RegExp]" !== Object.prototype.toString.call(b)) return D.apply(this, arguments);
                var e, f, g, h, i = [],
                    j = (b.ignoreCase ? "i" : "") + (b.multiline ? "m" : "") + (b.extended ? "x" : "") + (b.sticky ? "y" : ""),
                    k = 0,
                    b = new RegExp(b.source, j + "g");
                for (d += "", a || (e = new RegExp("^" + b.source + "$(?!\\s)", j)), c = void 0 === c ? -1 >>> 0 : c >>> 0;
                    (f = b.exec(d)) && (g = f.index + f[0].length, !(g > k && (i.push(d.slice(k, f.index)), !a && f.length > 1 && f[0].replace(e, function() {
                        for (var a = 1; a < arguments.length - 2; a++) void 0 === arguments[a] && (f[a] = void 0)
                    }), f.length > 1 && f.index < d.length && Array.prototype.push.apply(i, f.slice(1)), h = f[0].length, k = g, i.length >= c)));) b.lastIndex === f.index && b.lastIndex++;
            	
                return k === d.length ? (h || !b.test("")) && i.push("") : i.push(d.slice(k)), i.length > c ? i.slice(0, c) : i
            }
        }() : "0".split(void 0, 0).length && (String.prototype.split = function(a, b) {
            return void 0 === a && 0 === b ? [] : D.apply(this, arguments)
        }), "".substr && "b" !== "0b".substr(-1)) {
        var E = String.prototype.substr;
        String.prototype.substr = function(a, b) {
            return E.call(this, 0 > a ? (a = this.length + a) < 0 ? 0 : a : a, b)
        }
    }
    var F = "	\n\f\r Â áš€á Žâ€€â€�â€‚â€ƒâ€„â€…â€†â€‡â€ˆâ€‰â€Šâ€¯â�Ÿã€€\u2028\u2029ï»¿";
    if (!String.prototype.trim || F.trim()) {
        F = "[" + F + "]";
        var G = new RegExp("^" + F + F + "*"),
            H = new RegExp(F + F + "*$");
        String.prototype.trim = function() {
            if (void 0 === this || null === this) throw new TypeError("can't convert " + this + " to object");
            return String(this).replace(G, "").replace(H, "")
        }
    }
    var I = function(a) {
        if (null == a) throw new TypeError("can't convert " + a + " to object");
        return Object(a)
    }
}),
function() {
    "use strict";
    if ((!Element.prototype.addEventListener || !Element.prototype.removeEventListener) && Object.defineProperty) {
        var a = window.Event.prototype;
        a.preventDefault = function() {
            this.returnValue = !1
        }, a.stopPropagation = function() {
            this.cancelBubble = !0
        }, Object.defineProperty(a, "bubbles", {
            get: function() {
                var a, b = ["select", "scroll", "click", "dblclick", "mousedown", "mousemove", "mouseout", "mouseover", "mouseup", "wheel", "textinput", "keydown", "keypress", "keyup"],
                    c = b.length,
                    d = this.type;
                for (a = 0; c > a; a++)
                    if (d === b[a]) return !0;
                return !1
            }
        }), Object.defineProperty(a, "defaultPrevented", {
            get: function() {
                var a = this.returnValue;
                return a === !1 ? !0 : !1
            }
        }), Object.defineProperty(a, "relatedTarget", {
            get: function() {
                var a = this.type;
                return "mouseover" === a || "mouseout" === a ? "mouseover" === a ? this.fromElement : this.toElement : null
            }
        }), Object.defineProperty(a, "target", {
            get: function() {
                return this.srcElement
            }
        });
        var b = [],
            c = function(a, c) {
                var d, e, f, g = this;
                c && (e = function(a) {
                    a.currentTarget = g, c.handleEvent ? c.handleEvent(a) : c.call(g, a)
                }, "DOMContentLoaded" === a ? (f = function(a) {
                    "complete" === document.readyState && e(a)
                }, document.attachEvent("onreadystatechange", f), b.push({
                    object: this,
                    type: a,
                    listener: c,
                    wrapper: f
                }), "complete" === document.readyState && (d = document.createEventObject(), e(d))) : (this.attachEvent("on" + a, e), b.push({
                    object: this,
                    type: a,
                    listener: c,
                    wrapper: e
                })))
            },
            d = function(a, c) {
                for (var d, e = 0, f = b.length; f > e;) {
                    if (d = b[e], d.object === this && d.type === a && d.listener === c) {
                        "DOMContentLoaded" === a ? this.detachEvent("onreadystatechange", d.wrapper) : this.detachEvent("on" + a, d.wrapper);
                        break
                    }
                    e += 1
                }
            };
        Element.prototype.addEventListener = c, Element.prototype.removeEventListener = d, HTMLDocument.prototype.addEventListener = c, HTMLDocument.prototype.removeEventListener = d, Window.prototype.addEventListener = c, Window.prototype.removeEventListener = d
    }
}(),
function() {
    function a(a, b, c) {
        for (var d = (c || 0) - 1, e = a ? a.length : 0; ++d < e;)
            if (a[d] === b) return d;
        return -1
    }

    function b(b, c) {
        var d = typeof c;
        if (b = b.cache, "boolean" == d || null == c) return b[c] ? 0 : -1;
        "number" != d && "string" != d && (d = "object");
        var e = "number" == d ? c : u + c;
        return b = (b = b[d]) && b[e], "object" == d ? b && a(b, c) > -1 ? 0 : -1 : b ? 0 : -1
    }

    function c(a) {
        var b = this.cache,
            c = typeof a;
        if ("boolean" == c || null == a) b[a] = !0;
        else {
            "number" != c && "string" != c && (c = "object");
            var d = "number" == c ? a : u + a,
                e = b[c] || (b[c] = {});
            "object" == c ? (e[d] || (e[d] = [])).push(a) : e[d] = !0
        }
    }

    function d(a) {
        return a.charCodeAt(0)
    }

    function e(a, b) {
        for (var c = a.criteria, d = b.criteria, e = -1, f = c.length; ++e < f;) {
            var g = c[e],
                h = d[e];
            if (g !== h) {
                if (g > h || "undefined" == typeof g) return 1;
                if (h > g || "undefined" == typeof h) return -1
            }
        }
        return a.index - b.index
    }

    function f(a) {
        var b = -1,
            d = a.length,
            e = a[0],
            f = a[d / 2 | 0],
            g = a[d - 1];
        if (e && "object" == typeof e && f && "object" == typeof f && g && "object" == typeof g) return !1;
        var h = i();
        h["false"] = h["null"] = h["true"] = h.undefined = !1;
        var j = i();
        for (j.array = a, j.cache = h, j.push = c; ++b < d;) j.push(a[b]);
        return j
    }

    function g(a) {
        return "\\" + _[a]
    }

    function h() {
        return q.pop() || []
    }

    function i() {
        return r.pop() || {
            array: null,
            cache: null,
            criteria: null,
            "false": !1,
            index: 0,
            "null": !1,
            number: null,
            object: null,
            push: null,
            string: null,
            "true": !1,
            undefined: !1,
            value: null
        }
    }

    function j(a) {
        return "function" != typeof a.toString && "string" == typeof(a + "")
    }

    function l(a) {
        a.length = 0, q.length < w && q.push(a)
    }

    function m(a) {
        var b = a.cache;
        b && m(b), a.array = a.cache = a.criteria = a.object = a.number = a.string = a.value = null, r.length < w && r.push(a)
    }

    function n(a, b, c) {
        b || (b = 0), "undefined" == typeof c && (c = a ? a.length : 0);
        for (var d = -1, e = c - b || 0, f = Array(0 > e ? 0 : e); ++d < e;) f[d] = a[b + d];
        return f
    }

    function o(c) {
        function q(a) {
            return a && "object" == typeof a && !ke(a) && Rd.call(a, "__wrapped__") ? a : new r(a)
        }

        function r(a, b) {
            this.__chain__ = !!b, this.__wrapped__ = a
        }

        function w(a) {
            function b() {
                if (d) {
                    var a = n(d);
                    Sd.apply(a, arguments)
                }
                if (this instanceof b) {
                    var f = bb(c.prototype),
                        g = c.apply(f, a || arguments);
                    return Lb(g) ? g : f
                }
                return c.apply(e, a || arguments)
            }
            var c = a[0],
                d = a[2],
                e = a[4];
            return je(b, a), b
        }

        function _(a, b, c, d, e) {
            if (c) {
                var f = c(a);
                if ("undefined" != typeof f) return f
            }
            var g = Lb(a);
            if (!g) return a;
            var i = Kd.call(a);
            if (!W[i] || !he.nodeClass && j(a)) return a;
            var k = fe[i];
            switch (i) {
                case O:
                case P:
                    return new k(+a);
                case S:
                case V:
                    return new k(a);
                case U:
                    return f = k(a.source, C.exec(a)), f.lastIndex = a.lastIndex, f
            }
            var m = ke(a);
            if (b) {
                var o = !d;
                d || (d = h()), e || (e = h());
                for (var p = d.length; p--;)
                    if (d[p] == a) return e[p];
                f = m ? k(a.length) : {}
            } else f = m ? n(a) : ve({}, a);
            return m && (Rd.call(a, "index") && (f.index = a.index), Rd.call(a, "input") && (f.input = a.input)), b ? (d.push(a), e.push(f), (m ? ue : ye)(a, function(a, g) {
                f[g] = _(a, b, c, d, e)
            }), o && (l(d), l(e)), f) : f
        }

        function bb(a) {
            return Lb(a) ? Yd(a) : {}
        }

        function cb(a, b, c) {
            if ("function" != typeof a) return ed;
            if ("undefined" == typeof b || !("prototype" in a)) return a;
            var d = a.__bindData__;
            if ("undefined" == typeof d && (he.funcNames && (d = !a.name), d = d || !he.funcDecomp, !d)) {
                var e = Pd.call(a);
                he.funcNames || (d = !D.test(e)), d || (d = H.test(e), je(a, d))
            }
            if (d === !1 || d !== !0 && 1 & d[1]) return a;
            switch (c) {
                case 1:
                    return function(c) {
                        return a.call(b, c)
                    };
                case 2:
                    return function(c, d) {
                        return a.call(b, c, d)
                    };
                case 3:
                    return function(c, d, e) {
                        return a.call(b, c, d, e)
                    };
                case 4:
                    return function(c, d, e, f) {
                        return a.call(b, c, d, e, f)
                    }
            }
            return Pc(a, b)
        }

        function db(a) {
            function b() {
                var a = i ? g : this;
                if (e) {
                    var o = n(e);
                    Sd.apply(o, arguments)
                }
                if ((f || k) && (o || (o = n(arguments)), f && Sd.apply(o, f), k && o.length < h)) return d |= 16, db([c, l ? d : -4 & d, o, null, g, h]);
                if (o || (o = arguments), j && (c = a[m]), this instanceof b) {
                    a = bb(c.prototype);
                    var p = c.apply(a, o);
                    return Lb(p) ? p : a
                }
                return c.apply(a, o)
            }
            var c = a[0],
                d = a[1],
                e = a[2],
                f = a[3],
                g = a[4],
                h = a[5],
                i = 1 & d,
                j = 2 & d,
                k = 4 & d,
                l = 8 & d,
                m = c;
            return je(b, a), b
        }

        function eb(c, d) {
            var e = -1,
                g = pb(),
                h = c ? c.length : 0,
                i = h >= v && g === a,
                j = [];
            if (i) {
                var k = f(d);
                k ? (g = b, d = k) : i = !1
            }
            for (; ++e < h;) {
                var l = c[e];
                g(d, l) < 0 && j.push(l)
            }
            return i && m(d), j
        }

        function gb(a, b, c, d) {
            for (var e = (d || 0) - 1, f = a ? a.length : 0, g = []; ++e < f;) {
                var h = a[e];
                if (h && "object" == typeof h && "number" == typeof h.length && (ke(h) || tb(h))) {
                    b || (h = gb(h, b, c));
                    var i = -1,
                        j = h.length,
                        k = g.length;
                    for (g.length += j; ++i < j;) g[k++] = h[i]
                } else c || g.push(h)
            }
            return g
        }

        function hb(a, b, c, d, e, f) {
            if (c) {
                var g = c(a, b);
                if ("undefined" != typeof g) return !!g
            }
            if (a === b) return 0 !== a || 1 / a == 1 / b;
            var i = typeof a,
                k = typeof b;
            if (!(a !== a || a && $[i] || b && $[k])) return !1;
            if (null == a || null == b) return a === b;
            var m = Kd.call(a),
                n = Kd.call(b);
            if (m == M && (m = T), n == M && (n = T), m != n) return !1;
            switch (m) {
                case O:
                case P:
                    return +a == +b;
                case S:
                    return a != +a ? b != +b : 0 == a ? 1 / a == 1 / b : a == +b;
                case U:
                case V:
                    return a == Dd(b)
            }
            var o = m == N;
            if (!o) {
                var p = Rd.call(a, "__wrapped__"),
                    q = Rd.call(b, "__wrapped__");
                if (p || q) return hb(p ? a.__wrapped__ : a, q ? b.__wrapped__ : b, c, d, e, f);
                if (m != T || !he.nodeClass && (j(a) || j(b))) return !1;
                var r = !he.argsObject && tb(a) ? Bd : a.constructor,
                    s = !he.argsObject && tb(b) ? Bd : b.constructor;
                if (r != s && !(Kb(r) && r instanceof r && Kb(s) && s instanceof s) && "constructor" in a && "constructor" in b) return !1
            }
            var t = !e;
            e || (e = h()), f || (f = h());
            for (var u = e.length; u--;)
                if (e[u] == a) return f[u] == b;
            var v = 0;
            if (g = !0, e.push(a), f.push(b), o) {
                if (u = a.length, v = b.length, g = v == u, g || d)
                    for (; v--;) {
                        var w = u,
                            x = b[v];
                        if (d)
                            for (; w-- && !(g = hb(a[w], x, c, d, e, f)););
                        else if (!(g = hb(a[v], x, c, d, e, f))) break
                    }
            } else xe(b, function(b, h, i) {
                return Rd.call(i, h) ? (v++, g = Rd.call(a, h) && hb(a[h], b, c, d, e, f)) : void 0
            }), g && !d && xe(a, function(a, b, c) {
                return Rd.call(c, b) ? g = --v > -1 : void 0
            });
            return e.pop(), f.pop(), t && (l(e), l(f)), g
        }

        function ib(a, b, c, d, e) {
            (ke(b) ? dc : ye)(b, function(b, f) {
                var g, h, i = b,
                    j = a[f];
                if (b && ((h = ke(b)) || ze(b))) {
                    for (var k = d.length; k--;)
                        if (g = d[k] == b) {
                            j = e[k];
                            break
                        }
                    if (!g) {
                        var l;
                        c && (i = c(j, b), (l = "undefined" != typeof i) && (j = i)), l || (j = h ? ke(j) ? j : [] : ze(j) ? j : {}), d.push(b), e.push(j), l || ib(j, b, c, d, e)
                    }
                } else c && (i = c(j, b), "undefined" == typeof i && (i = b)), "undefined" != typeof i && (j = i);
                a[f] = j
            })
        }

        function jb(a, b) {
            return a + Od(ee() * (b - a + 1))
        }

        function kb(c, d, e) {
            var g = -1,
                i = pb(),
                j = c ? c.length : 0,
                k = [],
                n = !d && j >= v && i === a,
                o = e || n ? h() : k;
            if (n) {
                var p = f(o);
                i = b, o = p
            }
            for (; ++g < j;) {
                var q = c[g],
                    r = e ? e(q, g, c) : q;
                (d ? !g || o[o.length - 1] !== r : i(o, r) < 0) && ((e || n) && o.push(r), k.push(q))
            }
            return n ? (l(o.array), m(o)) : e && l(o), k
        }

        function lb(a) {
            return function(b, c, d) {
                var e = {};
                if (c = q.createCallback(c, d, 3), ke(b))
                    for (var f = -1, g = b.length; ++f < g;) {
                        var h = b[f];
                        a(e, h, c(h, f, b), b)
                    } else ue(b, function(b, d, f) {
                        a(e, b, c(b, d, f), f)
                    });
                return e
            }
        }

        function mb(a, b, c, d, e, f) {
            var g = 1 & b,
                h = 2 & b,
                i = 4 & b,
                j = 16 & b,
                k = 32 & b;
            if (!h && !Kb(a)) throw new Ed;
            j && !c.length && (b &= -17, j = c = !1), k && !d.length && (b &= -33, k = d = !1);
            var l = a && a.__bindData__;
            if (l && l !== !0) return l = n(l), l[2] && (l[2] = n(l[2])), l[3] && (l[3] = n(l[3])), !g || 1 & l[1] || (l[4] = e), !g && 1 & l[1] && (b |= 8), !i || 4 & l[1] || (l[5] = f), j && Sd.apply(l[2] || (l[2] = []), c), k && Wd.apply(l[3] || (l[3] = []), d), l[1] |= b, mb.apply(null, l);
            var m = 1 == b || 17 === b ? w : db;
            return m([a, b, c, d, e, f])
        }

        function nb() {
            Z.shadowedProps = K, Z.array = Z.bottom = Z.loop = Z.top = "", Z.init = "iterable", Z.useHas = !0;
            for (var a, b = 0; a = arguments[b]; b++)
                for (var c in a) Z[c] = a[c];
            var d = Z.args;
            Z.firstArg = /^[^,]+/.exec(d)[0];
            var e = yd("baseCreateCallback, errorClass, errorProto, hasOwnProperty, indicatorObject, isArguments, isArray, isString, keys, objectProto, objectTypes, nonEnumProps, stringClass, stringProto, toString", "return function(" + d + ") {\n" + ie(Z) + "\n}");
            return e(cb, Q, Gd, Rd, t, tb, ke, Qb, Z.keys, Hd, $, ge, V, Id, Kd)
        }

        function ob(a) {
            return qe[a]
        }

        function pb() {
            var b = (b = q.indexOf) === yc ? a : b;
            return b
        }

        function qb(a) {
            return "function" == typeof a && Ld.test(a)
        }

        function rb(a) {
            var b, c;
            return !a || Kd.call(a) != T || (b = a.constructor, Kb(b) && !(b instanceof b)) || !he.argsClass && tb(a) || !he.nodeClass && j(a) ? !1 : he.ownLast ? (xe(a, function(a, b, d) {
                return c = Rd.call(d, b), !1
            }), c !== !1) : (xe(a, function(a, b) {
                c = b
            }), "undefined" == typeof c || Rd.call(a, c))
        }

        function sb(a) {
            return re[a]
        }

        function tb(a) {
            return a && "object" == typeof a && "number" == typeof a.length && Kd.call(a) == M || !1
        }

        function ub(a, b, c, d) {
            return "boolean" != typeof b && null != b && (d = c, c = b, b = !1), _(a, b, "function" == typeof c && cb(c, d, 1))
        }

        function vb(a, b, c) {
            return _(a, !0, "function" == typeof b && cb(b, c, 1))
        }

        function wb(a, b) {
            var c = bb(a);
            return b ? ve(c, b) : c
        }

        function xb(a, b, c) {
            var d;
            return b = q.createCallback(b, c, 3), ye(a, function(a, c, e) {
                return b(a, c, e) ? (d = c, !1) : void 0
            }), d
        }

        function yb(a, b, c) {
            var d;
            return b = q.createCallback(b, c, 3), Ab(a, function(a, c, e) {
                return b(a, c, e) ? (d = c, !1) : void 0
            }), d
        }

        function zb(a, b, c) {
            var d = [];
            xe(a, function(a, b) {
                d.push(b, a)
            });
            var e = d.length;
            for (b = cb(b, c, 3); e-- && b(d[e--], d[e], a) !== !1;);
            return a
        }

        function Ab(a, b, c) {
            var d = me(a),
                e = d.length;
            for (b = cb(b, c, 3); e--;) {
                var f = d[e];
                if (b(a[f], f, a) === !1) break
            }
            return a
        }

        function Bb(a) {
            var b = [];
            return xe(a, function(a, c) {
                Kb(a) && b.push(c)
            }), b.sort()
        }

        function Cb(a, b) {
            return a ? Rd.call(a, b) : !1
        }

        function Db(a) {
            for (var b = -1, c = me(a), d = c.length, e = {}; ++b < d;) {
                var f = c[b];
                e[a[f]] = f
            }
            return e
        }

        function Eb(a) {
            return a === !0 || a === !1 || a && "object" == typeof a && Kd.call(a) == O || !1
        }

        function Fb(a) {
            return a && "object" == typeof a && Kd.call(a) == P || !1
        }

        function Gb(a) {
            return a && 1 === a.nodeType || !1
        }

        function Hb(a) {
            var b = !0;
            if (!a) return b;
            var c = Kd.call(a),
                d = a.length;
            return c == N || c == V || (he.argsClass ? c == M : tb(a)) || c == T && "number" == typeof d && Kb(a.splice) ? !d : (ye(a, function() {
                return b = !1
            }), b)
        }

        function Ib(a, b, c, d) {
            return hb(a, b, "function" == typeof c && cb(c, d, 2))
        }

        function Jb(a) {
            return $d(a) && !_d(parseFloat(a))
        }

        function Kb(a) {
            return "function" == typeof a
        }

        function Lb(a) {
            return !(!a || !$[typeof a])
        }

        function Mb(a) {
            return Ob(a) && a != +a
        }

        function Nb(a) {
            return null === a
        }

        function Ob(a) {
            return "number" == typeof a || a && "object" == typeof a && Kd.call(a) == S || !1
        }

        function Pb(a) {
            return a && $[typeof a] && Kd.call(a) == U || !1
        }

        function Qb(a) {
            return "string" == typeof a || a && "object" == typeof a && Kd.call(a) == V || !1
        }

        function Rb(a) {
            return "undefined" == typeof a
        }

        function Sb(a, b, c) {
            var d = {};
            return b = q.createCallback(b, c, 3), ye(a, function(a, c, e) {
                d[c] = b(a, c, e)
            }), d
        }

        function Tb(a) {
            var b = arguments,
                c = 2;
            if (!Lb(a)) return a;
            if ("number" != typeof b[2] && (c = b.length), c > 3 && "function" == typeof b[c - 2]) var d = cb(b[--c - 1], b[c--], 2);
            else c > 2 && "function" == typeof b[c - 1] && (d = b[--c]);
            for (var e = n(arguments, 1, c), f = -1, g = h(), i = h(); ++f < c;) ib(a, e[f], d, g, i);
            return l(g), l(i), a
        }

        function Ub(a, b, c) {
            var d = {};
            if ("function" != typeof b) {
                var e = [];
                xe(a, function(a, b) {
                    e.push(b)
                }), e = eb(e, gb(arguments, !0, !1, 1));
                for (var f = -1, g = e.length; ++f < g;) {
                    var h = e[f];
                    d[h] = a[h]
                }
            } else b = q.createCallback(b, c, 3), xe(a, function(a, c, e) {
                b(a, c, e) || (d[c] = a)
            });
            return d
        }

        function Vb(a) {
            for (var b = -1, c = me(a), d = c.length, e = ud(d); ++b < d;) {
                var f = c[b];
                e[b] = [f, a[f]]
            }
            return e
        }

        function Wb(a, b, c) {
            var d = {};
            if ("function" != typeof b)
                for (var e = -1, f = gb(arguments, !0, !1, 1), g = Lb(a) ? f.length : 0; ++e < g;) {
                    var h = f[e];
                    h in a && (d[h] = a[h])
                } else b = q.createCallback(b, c, 3), xe(a, function(a, c, e) {
                    b(a, c, e) && (d[c] = a)
                });
            return d
        }

        function Xb(a, b, c, d) {
            var e = ke(a);
            if (null == c)
                if (e) c = [];
                else {
                    var f = a && a.constructor,
                        g = f && f.prototype;
                    c = bb(g)
                }
            return b && (b = q.createCallback(b, d, 4), (e ? ue : ye)(a, function(a, d, e) {
                return b(c, a, d, e)
            })), c
        }

        function Yb(a) {
            for (var b = -1, c = me(a), d = c.length, e = ud(d); ++b < d;) e[b] = a[c[b]];
            return e
        }

        function Zb(a) {
            var b = arguments,
                c = -1,
                d = gb(b, !0, !1, 1),
                e = b[2] && b[2][b[1]] === a ? 1 : d.length,
                f = ud(e);
            for (he.unindexedChars && Qb(a) && (a = a.split("")); ++c < e;) f[c] = a[d[c]];
            return f
        }

        function $b(a, b, c) {
            var d = -1,
                e = pb(),
                f = a ? a.length : 0,
                g = !1;
            return c = (0 > c ? be(0, f + c) : c) || 0, ke(a) ? g = e(a, b, c) > -1 : "number" == typeof f ? g = (Qb(a) ? a.indexOf(b, c) : e(a, b, c)) > -1 : ue(a, function(a) {
                return ++d >= c ? !(g = a === b) : void 0
            }), g
        }

        function _b(a, b, c) {
            var d = !0;
            if (b = q.createCallback(b, c, 3), ke(a))
                for (var e = -1, f = a.length; ++e < f && (d = !!b(a[e], e, a)););
            else ue(a, function(a, c, e) {
                return d = !!b(a, c, e)
            });
            return d
        }

        function ac(a, b, c) {
            var d = [];
            if (b = q.createCallback(b, c, 3), ke(a))
                for (var e = -1, f = a.length; ++e < f;) {
                    var g = a[e];
                    b(g, e, a) && d.push(g)
                } else ue(a, function(a, c, e) {
                    b(a, c, e) && d.push(a)
                });
            return d
        }

        function bc(a, b, c) {
            if (b = q.createCallback(b, c, 3), !ke(a)) {
                var d;
                return ue(a, function(a, c, e) {
                    return b(a, c, e) ? (d = a, !1) : void 0
                }), d
            }
            for (var e = -1, f = a.length; ++e < f;) {
                var g = a[e];
                if (b(g, e, a)) return g
            }
        }

        function cc(a, b, c) {
            var d;
            return b = q.createCallback(b, c, 3), ec(a, function(a, c, e) {
                return b(a, c, e) ? (d = a, !1) : void 0
            }), d
        }

        function dc(a, b, c) {
            if (b && "undefined" == typeof c && ke(a))
                for (var d = -1, e = a.length; ++d < e && b(a[d], d, a) !== !1;);
            else ue(a, b, c);
            return a
        }

        function ec(a, b, c) {
            var d = a,
                e = a ? a.length : 0;
            if (b = b && "undefined" == typeof c ? b : cb(b, c, 3), ke(a))
                for (; e-- && b(a[e], e, a) !== !1;);
            else {
                if ("number" != typeof e) {
                    var f = me(a);
                    e = f.length
                } else he.unindexedChars && Qb(a) && (d = a.split(""));
                ue(a, function(a, c, g) {
                    return c = f ? f[--e] : --e, b(d[c], c, g)
                })
            }
            return a
        }

        function fc(a, b) {
            var c = n(arguments, 2),
                d = -1,
                e = "function" == typeof b,
                f = a ? a.length : 0,
                g = ud("number" == typeof f ? f : 0);
            return dc(a, function(a) {
                g[++d] = (e ? b : a[b]).apply(a, c)
            }), g
        }

        function gc(a, b, c) {
            var d = -1,
                e = a ? a.length : 0,
                f = ud("number" == typeof e ? e : 0);
            if (b = q.createCallback(b, c, 3), ke(a))
                for (; ++d < e;) f[d] = b(a[d], d, a);
            else ue(a, function(a, c, e) {
                f[++d] = b(a, c, e)
            });
            return f
        }

        function hc(a, b, c) {
            var e = -1 / 0,
                f = e;
            if ("function" != typeof b && c && c[b] === a && (b = null), null == b && ke(a))
                for (var g = -1, h = a.length; ++g < h;) {
                    var i = a[g];
                    i > f && (f = i)
                } else b = null == b && Qb(a) ? d : q.createCallback(b, c, 3), ue(a, function(a, c, d) {
                    var g = b(a, c, d);
                    g > e && (e = g, f = a)
                });
            return f
        }

        function ic(a, b, c) {
            var e = 1 / 0,
                f = e;
            if ("function" != typeof b && c && c[b] === a && (b = null), null == b && ke(a))
                for (var g = -1, h = a.length; ++g < h;) {
                    var i = a[g];
                    f > i && (f = i)
                } else b = null == b && Qb(a) ? d : q.createCallback(b, c, 3), ue(a, function(a, c, d) {
                    var g = b(a, c, d);
                    e > g && (e = g, f = a)
                });
            return f
        }

        function jc(a, b, c, d) {
            var e = arguments.length < 3;
            if (b = q.createCallback(b, d, 4), ke(a)) {
                var f = -1,
                    g = a.length;
                for (e && (c = a[++f]); ++f < g;) c = b(c, a[f], f, a)
            } else ue(a, function(a, d, f) {
                c = e ? (e = !1, a) : b(c, a, d, f)
            });
            return c
        }

        function kc(a, b, c, d) {
            var e = arguments.length < 3;
            return b = q.createCallback(b, d, 4), ec(a, function(a, d, f) {
                c = e ? (e = !1, a) : b(c, a, d, f)
            }), c
        }

        function lc(a, b, c) {
            return b = q.createCallback(b, c, 3), ac(a, function(a, c, d) {
                return !b(a, c, d)
            })
        }

        function mc(a, b, c) {
            if (a && "number" != typeof a.length ? a = Yb(a) : he.unindexedChars && Qb(a) && (a = a.split("")), null == b || c) return a ? a[jb(0, a.length - 1)] : p;
            var d = nc(a);
            return d.length = ce(be(0, b), d.length), d
        }

        function nc(a) {
            var b = -1,
                c = a ? a.length : 0,
                d = ud("number" == typeof c ? c : 0);
            return dc(a, function(a) {
                var c = jb(0, ++b);
                d[b] = d[c], d[c] = a
            }), d
        }

        function oc(a) {
            var b = a ? a.length : 0;
            return "number" == typeof b ? b : me(a).length
        }

        function pc(a, b, c) {
            var d;
            if (b = q.createCallback(b, c, 3), ke(a))
                for (var e = -1, f = a.length; ++e < f && !(d = b(a[e], e, a)););
            else ue(a, function(a, c, e) {
                return !(d = b(a, c, e))
            });
            return !!d
        }

        function qc(a, b, c) {
            var d = -1,
                f = ke(b),
                g = a ? a.length : 0,
                j = ud("number" == typeof g ? g : 0);
            for (f || (b = q.createCallback(b, c, 3)), dc(a, function(a, c, e) {
                    var g = j[++d] = i();
                    f ? g.criteria = gc(b, function(b) {
                        return a[b]
                    }) : (g.criteria = h())[0] = b(a, c, e), g.index = d, g.value = a
                }), g = j.length, j.sort(e); g--;) {
                var k = j[g];
                j[g] = k.value, f || l(k.criteria), m(k)
            }
            return j
        }

        function rc(a) {
            return a && "number" == typeof a.length ? he.unindexedChars && Qb(a) ? a.split("") : n(a) : Yb(a)
        }

        function sc(a) {
            for (var b = -1, c = a ? a.length : 0, d = []; ++b < c;) {
                var e = a[b];
                e && d.push(e)
            }
            return d
        }

        function tc(a) {
            return eb(a, gb(arguments, !0, !0, 1))
        }

        function uc(a, b, c) {
            var d = -1,
                e = a ? a.length : 0;
            for (b = q.createCallback(b, c, 3); ++d < e;)
                if (b(a[d], d, a)) return d;
            return -1
        }

        function vc(a, b, c) {
            var d = a ? a.length : 0;
            for (b = q.createCallback(b, c, 3); d--;)
                if (b(a[d], d, a)) return d;
            return -1
        }

        function wc(a, b, c) {
            var d = 0,
                e = a ? a.length : 0;
            if ("number" != typeof b && null != b) {
                var f = -1;
                for (b = q.createCallback(b, c, 3); ++f < e && b(a[f], f, a);) d++
            } else if (d = b, null == d || c) return a ? a[0] : p;
            return n(a, 0, ce(be(0, d), e))
        }

        function xc(a, b, c, d) {
            return "boolean" != typeof b && null != b && (d = c, c = "function" != typeof b && d && d[b] === a ? null : b, b = !1), null != c && (a = gc(a, c, d)), gb(a, b)
        }

        function yc(b, c, d) {
            if ("number" == typeof d) {
                var e = b ? b.length : 0;
                d = 0 > d ? be(0, e + d) : d || 0
            } else if (d) {
                var f = Hc(b, c);
                return b[f] === c ? f : -1
            }
            return a(b, c, d)
        }

        function zc(a, b, c) {
            var d = 0,
                e = a ? a.length : 0;
            if ("number" != typeof b && null != b) {
                var f = e;
                for (b = q.createCallback(b, c, 3); f-- && b(a[f], f, a);) d++
            } else d = null == b || c ? 1 : b || d;
            return n(a, 0, ce(be(0, e - d), e))
        }

        function Ac() {
            for (var c = [], d = -1, e = arguments.length, g = h(), i = pb(), j = i === a, k = h(); ++d < e;) {
                var n = arguments[d];
                (ke(n) || tb(n)) && (c.push(n), g.push(j && n.length >= v && f(d ? c[d] : k)))
            }
            var o = c[0],
                p = -1,
                q = o ? o.length : 0,
                r = [];
            a: for (; ++p < q;) {
                var s = g[0];
                if (n = o[p], (s ? b(s, n) : i(k, n)) < 0) {
                    for (d = e, (s || k).push(n); --d;)
                        if (s = g[d], (s ? b(s, n) : i(c[d], n)) < 0) continue a;
                    r.push(n)
                }
            }
            for (; e--;) s = g[e], s && m(s);
            return l(g), l(k), r
        }

        function Bc(a, b, c) {
            var d = 0,
                e = a ? a.length : 0;
            if ("number" != typeof b && null != b) {
                var f = e;
                for (b = q.createCallback(b, c, 3); f-- && b(a[f], f, a);) d++
            } else if (d = b, null == d || c) return a ? a[e - 1] : p;
            return n(a, be(0, e - d))
        }

        function Cc(a, b, c) {
            var d = a ? a.length : 0;
            for ("number" == typeof c && (d = (0 > c ? be(0, d + c) : ce(c, d - 1)) + 1); d--;)
                if (a[d] === b) return d;
            return -1
        }

        function Dc(a) {
            for (var b = arguments, c = 0, d = b.length, e = a ? a.length : 0; ++c < d;)
                for (var f = -1, g = b[c]; ++f < e;) a[f] === g && (Vd.call(a, f--, 1), e--);
            return a
        }

        function Ec(a, b, c) {
            a = +a || 0, c = "number" == typeof c ? c : +c || 1, null == b && (b = a, a = 0);
            for (var d = -1, e = be(0, Md((b - a) / (c || 1))), f = ud(e); ++d < e;) f[d] = a, a += c;
            return f
        }

        function Fc(a, b, c) {
            var d = -1,
                e = a ? a.length : 0,
                f = [];
            for (b = q.createCallback(b, c, 3); ++d < e;) {
                var g = a[d];
                b(g, d, a) && (f.push(g), Vd.call(a, d--, 1), e--)
            }
            return f
        }

        function Gc(a, b, c) {
            if ("number" != typeof b && null != b) {
                var d = 0,
                    e = -1,
                    f = a ? a.length : 0;
                for (b = q.createCallback(b, c, 3); ++e < f && b(a[e], e, a);) d++
            } else d = null == b || c ? 1 : be(0, b);
            return n(a, d)
        }

        function Hc(a, b, c, d) {
            var e = 0,
                f = a ? a.length : e;
            for (c = c ? q.createCallback(c, d, 1) : ed, b = c(b); f > e;) {
                var g = e + f >>> 1;
                c(a[g]) < b ? e = g + 1 : f = g
            }
            return e
        }

        function Ic() {
            return kb(gb(arguments, !0, !0))
        }

        function Jc(a, b, c, d) {
            return "boolean" != typeof b && null != b && (d = c, c = "function" != typeof b && d && d[b] === a ? null : b, b = !1), null != c && (c = q.createCallback(c, d, 3)), kb(a, b, c)
        }

        function Kc(a) {
            return eb(a, n(arguments, 1))
        }

        function Lc() {
            for (var a = -1, b = arguments.length; ++a < b;) {
                var c = arguments[a];
                if (ke(c) || tb(c)) var d = d ? kb(eb(d, c).concat(eb(c, d))) : c
            }
            return d || []
        }

        function Mc() {
            for (var a = arguments.length > 1 ? arguments : arguments[0], b = -1, c = a ? hc(De(a, "length")) : 0, d = ud(0 > c ? 0 : c); ++b < c;) d[b] = De(a, b);
            return d
        }

        function Nc(a, b) {
            var c = -1,
                d = a ? a.length : 0,
                e = {};
            for (b || !d || ke(a[0]) || (b = []); ++c < d;) {
                var f = a[c];
                b ? e[f] = b[c] : f && (e[f[0]] = f[1])
            }
            return e
        }

        function Oc(a, b) {
            if (!Kb(b)) throw new Ed;
            return function() {
                return --a < 1 ? b.apply(this, arguments) : void 0
            }
        }

        function Pc(a, b) {
            return arguments.length > 2 ? mb(a, 17, n(arguments, 2), null, b) : mb(a, 1, null, null, b)
        }

        function Qc(a) {
            for (var b = arguments.length > 1 ? gb(arguments, !0, !1, 1) : Bb(a), c = -1, d = b.length; ++c < d;) {
                var e = b[c];
                a[e] = mb(a[e], 1, null, null, a)
            }
            return a
        }

        function Rc(a, b) {
            return arguments.length > 2 ? mb(b, 19, n(arguments, 2), null, a) : mb(b, 3, null, null, a)
        }

        function Sc() {
            for (var a = arguments, b = a.length; b--;)
                if (!Kb(a[b])) throw new Ed;
            return function() {
                for (var b = arguments, c = a.length; c--;) b = [a[c].apply(this, b)];
                return b[0]
            }
        }

        function Tc(a, b) {
            return b = "number" == typeof b ? b : +b || a.length, mb(a, 4, null, null, null, b)
        }

        function Uc(a, b, c) {
            var d, e, f, g, h, i, j, k = 0,
                l = !1,
                m = !0;
            if (!Kb(a)) throw new Ed;
            if (b = be(0, b) || 0, c === !0) {
                var n = !0;
                m = !1
            } else Lb(c) && (n = c.leading, l = "maxWait" in c && (be(b, c.maxWait) || 0), m = "trailing" in c ? c.trailing : m);
            var o = function() {
                    var c = b - (Fe() - g);
                    if (0 >= c) {
                        e && Nd(e);
                        var l = j;
                        e = i = j = p, l && (k = Fe(), f = a.apply(h, d), i || e || (d = h = null))
                    } else i = Ud(o, c)
                },
                q = function() {
                    i && Nd(i), e = i = j = p, (m || l !== b) && (k = Fe(), f = a.apply(h, d), i || e || (d = h = null))
                };
            return function() {
                if (d = arguments, g = Fe(), h = this, j = m && (i || !n), l === !1) var c = n && !i;
                else {
                    e || n || (k = g);
                    var p = l - (g - k),
                        r = 0 >= p;
                    r ? (e && (e = Nd(e)), k = g, f = a.apply(h, d)) : e || (e = Ud(q, p))
                }
                return r && i ? i = Nd(i) : i || b === l || (i = Ud(o, b)), c && (r = !0, f = a.apply(h, d)), !r || i || e || (d = h = null), f
            }
        }

        function Vc(a) {
            if (!Kb(a)) throw new Ed;
            var b = n(arguments, 1);
            return Ud(function() {
                a.apply(p, b)
            }, 1)
        }

        function Wc(a, b) {
            if (!Kb(a)) throw new Ed;
            var c = n(arguments, 2);
            return Ud(function() {
                a.apply(p, c)
            }, b)
        }

        function Xc(a, b) {
            if (!Kb(a)) throw new Ed;
            var c = function() {
                var d = c.cache,
                    e = b ? b.apply(this, arguments) : u + arguments[0];
                return Rd.call(d, e) ? d[e] : d[e] = a.apply(this, arguments)
            };
            return c.cache = {}, c
        }

        function Yc(a) {
            var b, c;
            if (!Kb(a)) throw new Ed;
            return function() {
                return b ? c : (b = !0, c = a.apply(this, arguments), a = null, c)
            }
        }

        function Zc(a) {
            return mb(a, 16, n(arguments, 1))
        }

        function $c(a) {
            return mb(a, 32, null, n(arguments, 1))
        }

        function _c(a, b, c) {
            var d = !0,
                e = !0;
            if (!Kb(a)) throw new Ed;
            return c === !1 ? d = !1 : Lb(c) && (d = "leading" in c ? c.leading : d, e = "trailing" in c ? c.trailing : e), X.leading = d, X.maxWait = b, X.trailing = e, Uc(a, b, X)
        }

        function ad(a, b) {
            return mb(b, 16, [a])
        }

        function bd(a) {
            return function() {
                return a
            }
        }

        function cd(a, b, c) {
            var d = typeof a;
            if (null == a || "function" == d) return cb(a, b, c);
            if ("object" != d) return id(a);
            var e = me(a),
                f = e[0],
                g = a[f];
            return 1 != e.length || g !== g || Lb(g) ? function(b) {
                for (var c = e.length, d = !1; c-- && (d = hb(b[e[c]], a[e[c]], null, !0)););
                return d
            } : function(a) {
                var b = a[f];
                return g === b && (0 !== g || 1 / g == 1 / b)
            }
        }

        function dd(a) {
            return null == a ? "" : Dd(a).replace(te, ob)
        }

        function ed(a) {
            return a
        }

        function fd(a, b, c) {
            var d = !0,
                e = b && Bb(b);
            b && (c || e.length) || (null == c && (c = b), f = r, b = a, a = q, e = Bb(b)), c === !1 ? d = !1 : Lb(c) && "chain" in c && (d = c.chain);
            var f = a,
                g = Kb(f);
            dc(e, function(c) {
                var e = a[c] = b[c];
                g && (f.prototype[c] = function() {
                    var b = this.__chain__,
                        c = this.__wrapped__,
                        g = [c];
                    Sd.apply(g, arguments);
                    var h = e.apply(a, g);
                    if (d || b) {
                        if (c === h && Lb(h)) return this;
                        h = new f(h), h.__chain__ = b
                    }
                    return h
                })
            })
        }

        function gd() {
            return c._ = Jd, this
        }

        function hd() {}

        function id(a) {
            return function(b) {
                return b[a]
            }
        }

        function jd(a, b, c) {
            var d = null == a,
                e = null == b;
            if (null == c && ("boolean" == typeof a && e ? (c = a, a = 1) : e || "boolean" != typeof b || (c = b, e = !0)), d && e && (b = 1), a = +a || 0, e ? (b = a, a = 0) : b = +b || 0, c || a % 1 || b % 1) {
                var f = ee();
                return ce(a + f * (b - a + parseFloat("1e-" + ((f + "").length - 1))), b)
            }
            return jb(a, b)
        }

        function kd(a, b) {
            if (a) {
                var c = a[b];
                return Kb(c) ? a[b]() : c
            }
        }

        function ld(a, b, c) {
            var d = q.templateSettings;
            a = Dd(a || ""), c = we({}, c, d);
            var e, f = we({}, c.imports, d.imports),
                h = me(f),
                i = Yb(f),
                j = 0,
                k = c.interpolate || G,
                l = "__p += '",
                m = Cd((c.escape || G).source + "|" + k.source + "|" + (k === E ? B : G).source + "|" + (c.evaluate || G).source + "|$", "g");
            a.replace(m, function(b, c, d, f, h, i) {
                return d || (d = f), l += a.slice(j, i).replace(I, g), c && (l += "' +\n__e(" + c + ") +\n'"), h && (e = !0, l += "';\n" + h + ";\n__p += '"), d && (l += "' +\n((__t = (" + d + ")) == null ? '' : __t) +\n'"), j = i + b.length, b
            }), l += "';\n";
            var n = c.variable,
                o = n;
            o || (n = "obj", l = "with (" + n + ") {\n" + l + "\n}\n"), l = (e ? l.replace(y, "") : l).replace(z, "$1").replace(A, "$1;"), l = "function(" + n + ") {\n" + (o ? "" : n + " || (" + n + " = {});\n") + "var __t, __p = '', __e = _.escape" + (e ? ", __j = Array.prototype.join;\nfunction print() { __p += __j.call(arguments, '') }\n" : ";\n") + l + "return __p\n}";
            var r = "\n/*\n//# sourceURL=" + (c.sourceURL || "/lodash/template/source[" + L++ +"]") + "\n*/";
            try {
                var s = yd(h, "return " + l + r).apply(p, i)
            } catch (t) {
                throw t.source = l, t
            }
            return b ? s(b) : (s.source = l, s)
        }

        function md(a, b, c) {
            a = (a = +a) > -1 ? a : 0;
            var d = -1,
                e = ud(a);
            for (b = cb(b, c, 1); ++d < a;) e[d] = b(d);
            return e
        }

        function nd(a) {
            return null == a ? "" : Dd(a).replace(se, sb)
        }

        function od(a) {
            var b = ++s;
            return Dd(null == a ? "" : a) + b
        }

        function pd(a) {
            return a = new r(a), a.__chain__ = !0, a
        }

        function qd(a, b) {
            return b(a), a
        }

        function rd() {
            return this.__chain__ = !0, this
        }

        function sd() {
            return Dd(this.__wrapped__)
        }

        function td() {
            return this.__wrapped__
        }
        c = c ? fb.defaults(ab.Object(), c, fb.pick(ab, J)) : ab;
        var ud = c.Array,
            vd = c.Boolean,
            wd = c.Date,
            xd = c.Error,
            yd = c.Function,
            zd = c.Math,
            Ad = c.Number,
            Bd = c.Object,
            Cd = c.RegExp,
            Dd = c.String,
            Ed = c.TypeError,
            Fd = [],
            Gd = xd.prototype,
            Hd = Bd.prototype,
            Id = Dd.prototype,
            Jd = c._,
            Kd = Hd.toString,
            Ld = Cd("^" + Dd(Kd).replace(/[.*+?^${}()|[\]\\]/g, "\\$&").replace(/toString| for [^\]]+/g, ".*?") + "$"),
            Md = zd.ceil,
            Nd = c.clearTimeout,
            Od = zd.floor,
            Pd = yd.prototype.toString,
            Qd = qb(Qd = Bd.getPrototypeOf) && Qd,
            Rd = Hd.hasOwnProperty,
            Sd = Fd.push,
            Td = Hd.propertyIsEnumerable,
            Ud = c.setTimeout,
            Vd = Fd.splice,
            Wd = Fd.unshift,
            Xd = function() {
                try {
                    var a = {},
                        b = qb(b = Bd.defineProperty) && b,
                        c = b(a, a, a) && b
                } catch (d) {}
                return c
            }(),
            Yd = qb(Yd = Bd.create) && Yd,
            Zd = qb(Zd = ud.isArray) && Zd,
            $d = c.isFinite,
            _d = c.isNaN,
            ae = qb(ae = Bd.keys) && ae,
            be = zd.max,
            ce = zd.min,
            de = c.parseInt,
            ee = zd.random,
            fe = {};
        fe[N] = ud, fe[O] = vd, fe[P] = wd, fe[R] = yd, fe[T] = Bd, fe[S] = Ad, fe[U] = Cd, fe[V] = Dd;
        var ge = {};
        ge[N] = ge[P] = ge[S] = {
                constructor: !0,
                toLocaleString: !0,
                toString: !0,
                valueOf: !0
            }, ge[O] = ge[V] = {
                constructor: !0,
                toString: !0,
                valueOf: !0
            }, ge[Q] = ge[R] = ge[U] = {
                constructor: !0,
                toString: !0
            }, ge[T] = {
                constructor: !0
            },
            function() {
                for (var a = K.length; a--;) {
                    var b = K[a];
                    for (var c in ge) Rd.call(ge, c) && !Rd.call(ge[c], b) && (ge[c][b] = !1)
                }
            }(), r.prototype = q.prototype;
        var he = q.support = {};
        ! function() {
            var a = function() {
                    this.x = 1
                },
                b = {
                    0: 1,
                    length: 1
                },
                d = [];
            a.prototype = {
                valueOf: 1,
                y: 1
            };
            for (var e in new a) d.push(e);
            for (e in arguments);
            he.argsClass = Kd.call(arguments) == M, he.argsObject = arguments.constructor == Bd && !(arguments instanceof ud), he.enumErrorProps = Td.call(Gd, "message") || Td.call(Gd, "name"), he.enumPrototypes = Td.call(a, "prototype"), he.funcDecomp = !qb(c.WinRTError) && H.test(o), he.funcNames = "string" == typeof yd.name, he.nonEnumArgs = 0 != e, he.nonEnumShadows = !/valueOf/.test(d), he.ownLast = "x" != d[0], he.spliceObjects = (Fd.splice.call(b, 0, 1), !b[0]), he.unindexedChars = "x" [0] + Bd("x")[0] != "xx";
            try {
                he.nodeClass = !(Kd.call(document) == T && !({
                    toString: 0
                } + ""))
            } catch (f) {
                he.nodeClass = !0
            }
        }(1), q.templateSettings = {
            escape: /<%-([\s\S]+?)%>/g,
            evaluate: /<%([\s\S]+?)%>/g,
            interpolate: E,
            variable: "",
            imports: {
                _: q
            }
        };
        var ie = function(a) {
            var b = "var index, iterable = " + a.firstArg + ", result = " + a.init + ";\nif (!iterable) return result;\n" + a.top + ";";
            a.array ? (b += "\nvar length = iterable.length; index = -1;\nif (" + a.array + ") {  ", he.unindexedChars && (b += "\n  if (isString(iterable)) {\n    iterable = iterable.split('')\n  }  "), b += "\n  while (++index < length) {\n    " + a.loop + ";\n  }\n}\nelse {  ") : he.nonEnumArgs && (b += "\n  var length = iterable.length; index = -1;\n  if (length && isArguments(iterable)) {\n    while (++index < length) {\n      index += '';\n      " + a.loop + ";\n    }\n  } else {  "), he.enumPrototypes && (b += "\n  var skipProto = typeof iterable == 'function';\n  "), he.enumErrorProps && (b += "\n  var skipErrorProps = iterable === errorProto || iterable instanceof Error;\n  ");
            var c = [];
            if (he.enumPrototypes && c.push('!(skipProto && index == "prototype")'), he.enumErrorProps && c.push('!(skipErrorProps && (index == "message" || index == "name"))'), a.useHas && a.keys) b += "\n  var ownIndex = -1,\n      ownProps = objectTypes[typeof iterable] && keys(iterable),\n      length = ownProps ? ownProps.length : 0;\n\n  while (++ownIndex < length) {\n    index = ownProps[ownIndex];\n", c.length && (b += "    if (" + c.join(" && ") + ") {\n  "), b += a.loop + ";    ", c.length && (b += "\n    }"), b += "\n  }  ";
            else if (b += "\n  for (index in iterable) {\n", a.useHas && c.push("hasOwnProperty.call(iterable, index)"), c.length && (b += "    if (" + c.join(" && ") + ") {\n  "), b += a.loop + ";    ", c.length && (b += "\n    }"), b += "\n  }    ", he.nonEnumShadows) {
                for (b += "\n\n  if (iterable !== objectProto) {\n    var ctor = iterable.constructor,\n        isProto = iterable === (ctor && ctor.prototype),\n        className = iterable === stringProto ? stringClass : iterable === errorProto ? errorClass : toString.call(iterable),\n        nonEnum = nonEnumProps[className];\n      ", k = 0; 7 > k; k++) b += "\n    index = '" + a.shadowedProps[k] + "';\n    if ((!(isProto && nonEnum[index]) && hasOwnProperty.call(iterable, index))", a.useHas || (b += " || (!nonEnum[index] && iterable[index] !== objectProto[index])"), b += ") {\n      " + a.loop + ";\n    }      ";
                b += "\n  }    "
            }
            return (a.array || he.nonEnumArgs) && (b += "\n}"), b += a.bottom + ";\nreturn result"
        };
        Yd || (bb = function() {
            function a() {}
            return function(b) {
                if (Lb(b)) {
                    a.prototype = b;
                    var d = new a;
                    a.prototype = null
                }
                return d || c.Object()
            }
        }());
        var je = Xd ? function(a, b) {
            Y.value = b, Xd(a, "__bindData__", Y)
        } : hd;
        he.argsClass || (tb = function(a) {
            return a && "object" == typeof a && "number" == typeof a.length && Rd.call(a, "callee") && !Td.call(a, "callee") || !1
        });
        var ke = Zd || function(a) {
                return a && "object" == typeof a && "number" == typeof a.length && Kd.call(a) == N || !1
            },
            le = nb({
                args: "object",
                init: "[]",
                top: "if (!(objectTypes[typeof object])) return result",
                loop: "result.push(index)"
            }),
            me = ae ? function(a) {
                return Lb(a) ? he.enumPrototypes && "function" == typeof a || he.nonEnumArgs && a.length && tb(a) ? le(a) : ae(a) : []
            } : le,
            ne = {
                args: "collection, callback, thisArg",
                top: "callback = callback && typeof thisArg == 'undefined' ? callback : baseCreateCallback(callback, thisArg, 3)",
                array: "typeof length == 'number'",
                keys: me,
                loop: "if (callback(iterable[index], index, collection) === false) return result"
            },
            oe = {
                args: "object, source, guard",
                top: "var args = arguments,\n    argsIndex = 0,\n    argsLength = typeof guard == 'number' ? 2 : args.length;\nwhile (++argsIndex < argsLength) {\n  iterable = args[argsIndex];\n  if (iterable && objectTypes[typeof iterable]) {",
                keys: me,
                loop: "if (typeof result[index] == 'undefined') result[index] = iterable[index]",
                bottom: "  }\n}"
            },
            pe = {
                top: "if (!objectTypes[typeof iterable]) return result;\n" + ne.top,
                array: !1
            },
            qe = {
                "&": "&amp;",
                "<": "&lt;",
                ">": "&gt;",
                '"': "&quot;",
                "'": "&#39;"
            },
            re = Db(qe),
            se = Cd("(" + me(re).join("|") + ")", "g"),
            te = Cd("[" + me(qe).join("") + "]", "g"),
            ue = nb(ne),
            ve = nb(oe, {
                top: oe.top.replace(";", ";\nif (argsLength > 3 && typeof args[argsLength - 2] == 'function') {\n  var callback = baseCreateCallback(args[--argsLength - 1], args[argsLength--], 2);\n} else if (argsLength > 2 && typeof args[argsLength - 1] == 'function') {\n  callback = args[--argsLength];\n}"),
                loop: "result[index] = callback ? callback(result[index], iterable[index]) : iterable[index]"
            }),
            we = nb(oe),
            xe = nb(ne, pe, {
                useHas: !1
            }),
            ye = nb(ne, pe);
        Kb(/x/) && (Kb = function(a) {
            return "function" == typeof a && Kd.call(a) == R
        });
        var ze = Qd ? function(a) {
                if (!a || Kd.call(a) != T || !he.argsClass && tb(a)) return !1;
                var b = a.valueOf,
                    c = qb(b) && (c = Qd(b)) && Qd(c);
                return c ? a == c || Qd(a) == c : rb(a)
            } : rb,
            Ae = lb(function(a, b, c) {
                Rd.call(a, c) ? a[c] ++ : a[c] = 1
            }),
            Be = lb(function(a, b, c) {
                (Rd.call(a, c) ? a[c] : a[c] = []).push(b)
            }),
            Ce = lb(function(a, b, c) {
                a[c] = b
            }),
            De = gc,
            Ee = ac,
            Fe = qb(Fe = wd.now) && Fe || function() {
                return (new wd).getTime()
            },
            Ge = 8 == de(x + "08") ? de : function(a, b) {
                return de(Qb(a) ? a.replace(F, "") : a, b || 0)
            };
        return q.after = Oc, q.assign = ve, q.at = Zb, q.bind = Pc, q.bindAll = Qc, q.bindKey = Rc, q.chain = pd, q.compact = sc, q.compose = Sc, q.constant = bd, q.countBy = Ae, q.create = wb, q.createCallback = cd, q.curry = Tc, q.debounce = Uc, q.defaults = we, q.defer = Vc, q.delay = Wc, q.difference = tc, q.filter = ac, q.flatten = xc, q.forEach = dc, q.forEachRight = ec, q.forIn = xe, q.forInRight = zb, q.forOwn = ye, q.forOwnRight = Ab, q.functions = Bb, q.groupBy = Be, q.indexBy = Ce, q.initial = zc, q.intersection = Ac, q.invert = Db, q.invoke = fc, q.keys = me, q.map = gc, q.mapValues = Sb, q.max = hc, q.memoize = Xc, q.merge = Tb, q.min = ic, q.omit = Ub, q.once = Yc, q.pairs = Vb, q.partial = Zc, q.partialRight = $c, q.pick = Wb, q.pluck = De, q.property = id, q.pull = Dc, q.range = Ec, q.reject = lc, q.remove = Fc, q.rest = Gc, q.shuffle = nc, q.sortBy = qc, q.tap = qd, q.throttle = _c, q.times = md, q.toArray = rc, q.transform = Xb, q.union = Ic, q.uniq = Jc, q.values = Yb, q.where = Ee, q.without = Kc, q.wrap = ad, q.xor = Lc, q.zip = Mc, q.zipObject = Nc, q.collect = gc, q.drop = Gc, q.each = dc, q.eachRight = ec, q.extend = ve, q.methods = Bb, q.object = Nc, q.select = ac, q.tail = Gc, q.unique = Jc, q.unzip = Mc, fd(q), q.clone = ub, q.cloneDeep = vb, q.contains = $b, q.escape = dd, q.every = _b, q.find = bc, q.findIndex = uc, q.findKey = xb, q.findLast = cc, q.findLastIndex = vc, q.findLastKey = yb, q.has = Cb, q.identity = ed, q.indexOf = yc, q.isArguments = tb, q.isArray = ke, q.isBoolean = Eb, q.isDate = Fb, q.isElement = Gb, q.isEmpty = Hb, q.isEqual = Ib, q.isFinite = Jb, q.isFunction = Kb, q.isNaN = Mb, q.isNull = Nb, q.isNumber = Ob, q.isObject = Lb, q.isPlainObject = ze, q.isRegExp = Pb, q.isString = Qb, q.isUndefined = Rb, q.lastIndexOf = Cc, q.mixin = fd, q.noConflict = gd, q.noop = hd, q.now = Fe, q.parseInt = Ge, q.random = jd, q.reduce = jc, q.reduceRight = kc, q.result = kd, q.runInContext = o, q.size = oc, q.some = pc, q.sortedIndex = Hc, q.template = ld, q.unescape = nd, q.uniqueId = od, q.all = _b, q.any = pc, q.detect = bc, q.findWhere = bc, q.foldl = jc, q.foldr = kc, q.include = $b, q.inject = jc, fd(function() {
            var a = {};
            return ye(q, function(b, c) {
                q.prototype[c] || (a[c] = b)
            }), a
        }(), !1), q.first = wc, q.last = Bc, q.sample = mc, q.take = wc, q.head = wc, ye(q, function(a, b) {
            var c = "sample" !== b;
            q.prototype[b] || (q.prototype[b] = function(b, d) {
                var e = this.__chain__,
                    f = a(this.__wrapped__, b, d);
                return e || null != b && (!d || c && "function" == typeof b) ? new r(f, e) : f
            })
        }), q.VERSION = "2.4.1", q.prototype.chain = rd, q.prototype.toString = sd, q.prototype.value = td, q.prototype.valueOf = td, ue(["join", "pop", "shift"], function(a) {
            var b = Fd[a];
            q.prototype[a] = function() {
                var a = this.__chain__,
                    c = b.apply(this.__wrapped__, arguments);
                return a ? new r(c, a) : c
            }
        }), ue(["push", "reverse", "sort", "unshift"], function(a) {
            var b = Fd[a];
            q.prototype[a] = function() {
                return b.apply(this.__wrapped__, arguments), this
            }
        }), ue(["concat", "slice", "splice"], function(a) {
            var b = Fd[a];
            q.prototype[a] = function() {
                return new r(b.apply(this.__wrapped__, arguments), this.__chain__)
            }
        }), he.spliceObjects || ue(["pop", "shift", "splice"], function(a) {
            var b = Fd[a],
                c = "splice" == a;
            q.prototype[a] = function() {
                var a = this.__chain__,
                    d = this.__wrapped__,
                    e = b.apply(d, arguments);
                return 0 === d.length && delete d[0], a || c ? new r(e, a) : e
            }
        }), q
    }
    var p, q = [],
        r = [],
        s = 0,
        t = {},
        u = +new Date + "",
        v = 75,
        w = 40,
        x = " 	\fÂ ï»¿\n\r\u2028\u2029áš€á Žâ€€â€�â€‚â€ƒâ€„â€…â€†â€‡â€ˆâ€‰â€Šâ€¯â�Ÿã€€",
        y = /\b__p \+= '';/g,
        z = /\b(__p \+=) '' \+/g,
        A = /(__e\(.*?\)|\b__t\)) \+\n'';/g,
        B = /\$\{([^\\}]*(?:\\.[^\\}]*)*)\}/g,
        C = /\w*$/,
        D = /^\s*function[ \n\r\t]+\w/,
        E = /<%=([\s\S]+?)%>/g,
        F = RegExp("^[" + x + "]*0+(?=.$)"),
        G = /($^)/,
        H = /\bthis\b/,
        I = /['\n\r\t\u2028\u2029\\]/g,
        J = ["Array", "Boolean", "Date", "Error", "Function", "Math", "Number", "Object", "RegExp", "String", "_", "attachEvent", "clearTimeout", "isFinite", "isNaN", "parseInt", "setTimeout"],
        K = ["constructor", "hasOwnProperty", "isPrototypeOf", "propertyIsEnumerable", "toLocaleString", "toString", "valueOf"],
        L = 0,
        M = "[object Arguments]",
        N = "[object Array]",
        O = "[object Boolean]",
        P = "[object Date]",
        Q = "[object Error]",
        R = "[object Function]",
        S = "[object Number]",
        T = "[object Object]",
        U = "[object RegExp]",
        V = "[object String]",
        W = {};
    W[R] = !1, W[M] = W[N] = W[O] = W[P] = W[S] = W[T] = W[U] = W[V] = !0;
    var X = {
            leading: !1,
            maxWait: 0,
            trailing: !1
        },
        Y = {
            configurable: !1,
            enumerable: !1,
            value: null,
            writable: !1
        },
        Z = {
            args: "",
            array: null,
            bottom: "",
            firstArg: "",
            init: "",
            keys: null,
            loop: "",
            shadowedProps: null,
            support: null,
            top: "",
            useHas: !1
        },
        $ = {
            "boolean": !1,
            "function": !0,
            object: !0,
            number: !1,
            string: !1,
            undefined: !1
        },
        _ = {
            "\\": "\\",
            "'": "'",
            "\n": "n",
            "\r": "r",
            "	": "t",
            "\u2028": "u2028",
            "\u2029": "u2029"
        },
        ab = $[typeof window] && window || this,
        bb = $[typeof exports] && exports && !exports.nodeType && exports,
        cb = $[typeof module] && module && !module.nodeType && module,
        db = cb && cb.exports === bb && bb,
        eb = $[typeof global] && global;
    !eb || eb.global !== eb && eb.window !== eb || (ab = eb);
    var fb = o();
    "function" == typeof define && "object" == typeof define.amd && define.amd ? (ab._ = fb, define(function() {
        return fb
    })) : bb && cb ? db ? (cb.exports = fb)._ = fb : bb._ = fb : ab._ = fb
}.call(this), ! function(a) {
        function b() {
            this._events = {}, this._conf && c.call(this, this._conf)
        }

        function c(a) {
            a && (this._conf = a, a.delimiter && (this.delimiter = a.delimiter), a.maxListeners && (this._events.maxListeners = a.maxListeners), a.wildcard && (this.wildcard = a.wildcard), a.newListener && (this.newListener = a.newListener), this.wildcard && (this.listenerTree = {}))
        }

        function d(a) {
            this._events = {}, this.newListener = !1, c.call(this, a)
        }

        function e(a, b, c, d) {
            if (!c) return [];
            var f, g, h, i, j, k, l, m = [],
                n = b.length,
                o = b[d],
                p = b[d + 1];
            if (d === n && c._listeners) {
                if ("function" == typeof c._listeners) return a && a.push(c._listeners), [c];
                for (f = 0, g = c._listeners.length; g > f; f++) a && a.push(c._listeners[f]);
                return [c]
            }
            if ("*" === o || "**" === o || c[o]) {
                if ("*" === o) {
                    for (h in c) "_listeners" !== h && c.hasOwnProperty(h) && (m = m.concat(e(a, b, c[h], d + 1)));
                    return m
                }
                if ("**" === o) {
                    l = d + 1 === n || d + 2 === n && "*" === p, l && c._listeners && (m = m.concat(e(a, b, c, n)));
                    for (h in c) "_listeners" !== h && c.hasOwnProperty(h) && ("*" === h || "**" === h ? (c[h]._listeners && !l && (m = m.concat(e(a, b, c[h], n))), m = m.concat(e(a, b, c[h], d))) : m = h === p ? m.concat(e(a, b, c[h], d + 2)) : m.concat(e(a, b, c[h], d)));
                    return m
                }
                m = m.concat(e(a, b, c[o], d + 1))
            }
            if (i = c["*"], i && e(a, b, i, d + 1), j = c["**"])
                if (n > d) {
                    j._listeners && e(a, b, j, n);
                    for (h in j) "_listeners" !== h && j.hasOwnProperty(h) && (h === p ? e(a, b, j[h], d + 2) : h === o ? e(a, b, j[h], d + 1) : (k = {}, k[h] = j[h], e(a, b, {
                        "**": k
                    }, d + 1)))
                } else j._listeners ? e(a, b, j, n) : j["*"] && j["*"]._listeners && e(a, b, j["*"], n);
            return m
        }

        function f(a, b) {
        	
        	
        	
            a = "string" == typeof a ? a.split(this.delimiter) : a.slice();
            for (var c = 0, d = a.length; d > c + 1; c++)
                if ("**" === a[c] && "**" === a[c + 1]) return;
            for (var e = this.listenerTree, f = a.shift(); f;) {
                if (e[f] || (e[f] = {}), e = e[f], 0 === a.length) {
                    if (e._listeners) {
                        if ("function" == typeof e._listeners) e._listeners = [e._listeners, b];
                        else if (g(e._listeners) && (e._listeners.push(b), !e._listeners.warned)) {
                            var i = h;
                            "undefined" != typeof this._events.maxListeners && (i = this._events.maxListeners), i > 0 && e._listeners.length > i && (e._listeners.warned = !0, console.error("(node) warning: possible EventEmitter memory leak detected. %d listeners added. Use emitter.setMaxListeners() to increase limit.", e._listeners.length), console.trace())
                        }
                    } else e._listeners = b;
                    return !0
                }
                f = a.shift()
            }
            return !0
        }
        var g = Array.isArray ? Array.isArray : function(a) {
                return "[object Array]" === Object.prototype.toString.call(a)
            },
            h = 10;
        d.prototype.delimiter = ".", d.prototype.setMaxListeners = function(a) {
            this._events || b.call(this), this._events.maxListeners = a, this._conf || (this._conf = {}), this._conf.maxListeners = a
        }, d.prototype.event = "", d.prototype.once = function(a, b) {
            return this.many(a, 1, b), this
        }, d.prototype.many = function(a, b, c) {
            function d() {
                0 === --b && e.off(a, d), c.apply(this, arguments)
            }
            var e = this;
            if ("function" != typeof c) throw new Error("many only accepts instances of Function");
            return d._origin = c, this.on(a, d), e
        }, d.prototype.emit = function() {
            this._events || b.call(this);
            var a = arguments[0];
            if ("newListener" === a && !this.newListener && !this._events.newListener) return !1;
            if (this._all) {
                for (var c = arguments.length, d = new Array(c - 1), f = 1; c > f; f++) d[f - 1] = arguments[f];
                for (f = 0, c = this._all.length; c > f; f++) this.event = a, this._all[f].apply(this, d)
            }
            if ("error" === a && !(this._all || this._events.error || this.wildcard && this.listenerTree.error)) throw arguments[1] instanceof Error ? arguments[1] : new Error("Uncaught, unspecified 'error' event.");
            var g;
            if (this.wildcard) {
                g = [];
                var h = "string" == typeof a ? a.split(this.delimiter) : a.slice();
                e.call(this, g, h, this.listenerTree, 0)
            } else g = this._events[a];
            if ("function" == typeof g) {
                if (this.event = a, 1 === arguments.length) g.call(this);
                else if (arguments.length > 1) switch (arguments.length) {
                    case 2:
                        g.call(this, arguments[1]);
                        break;
                    case 3:
                        g.call(this, arguments[1], arguments[2]);
                        break;
                    default:
                        for (var c = arguments.length, d = new Array(c - 1), f = 1; c > f; f++) d[f - 1] = arguments[f];
                        g.apply(this, d)
                }
                return !0
            }
            if (g) {
                for (var c = arguments.length, d = new Array(c - 1), f = 1; c > f; f++) d[f - 1] = arguments[f];
                for (var i = g.slice(), f = 0, c = i.length; c > f; f++) this.event = a, i[f].apply(this, d);
                return i.length > 0 || this._all
            }
            return this._all
        }, d.prototype.on = function(a, c) {
            if ("function" == typeof a) return this.onAny(a), this;
            if ("function" != typeof c) throw new Error("on only accepts instances of Function");
            if (this._events || b.call(this), this.emit("newListener", a, c), this.wildcard) return f.call(this, a, c), this;
            if (this._events[a]) {
                if ("function" == typeof this._events[a]) this._events[a] = [this._events[a], c];
                else if (g(this._events[a]) && (this._events[a].push(c), !this._events[a].warned)) {
                    var d = h;
                    "undefined" != typeof this._events.maxListeners && (d = this._events.maxListeners), d > 0 && this._events[a].length > d && (this._events[a].warned = !0, console.error("(node) warning: possible EventEmitter memory leak detected. %d listeners added. Use emitter.setMaxListeners() to increase limit.", this._events[a].length), console.trace())
                }
            } else this._events[a] = c;
            return this
        }, d.prototype.onAny = function(a) {
            if (this._all || (this._all = []), "function" != typeof a) throw new Error("onAny only accepts instances of Function");
            return this._all.push(a), this
        }, d.prototype.addListener = d.prototype.on, d.prototype.off = function(a, b) {
            if ("function" != typeof b) throw new Error("removeListener only takes instances of Function");
            var c, d = [];
            if (this.wildcard) {
                var f = "string" == typeof a ? a.split(this.delimiter) : a.slice();
                d = e.call(this, null, f, this.listenerTree, 0)
            } else {
                if (!this._events[a]) return this;
                c = this._events[a], d.push({
                    _listeners: c
                })
            }
            for (var h = 0; h < d.length; h++) {
                var i = d[h];
                if (c = i._listeners, g(c)) {
                    for (var j = -1, k = 0, l = c.length; l > k; k++)
                        if (c[k] === b || c[k].listener && c[k].listener === b || c[k]._origin && c[k]._origin === b) {
                            j = k;
                            break
                        }
                    if (0 > j) continue;
                    return this.wildcard ? i._listeners.splice(j, 1) : this._events[a].splice(j, 1), 0 === c.length && (this.wildcard ? delete i._listeners : delete this._events[a]), this
                }(c === b || c.listener && c.listener === b || c._origin && c._origin === b) && (this.wildcard ? delete i._listeners : delete this._events[a])
            }
            return this
        }, d.prototype.offAny = function(a) {
            var b, c = 0,
                d = 0;
            if (a && this._all && this._all.length > 0) {
                for (b = this._all, c = 0, d = b.length; d > c; c++)
                    if (a === b[c]) return b.splice(c, 1), this
            } else this._all = [];
            return this
        }, d.prototype.removeListener = d.prototype.off, d.prototype.removeAllListeners = function(a) {
            if (0 === arguments.length) return !this._events || b.call(this), this;
            if (this.wildcard)
                for (var c = "string" == typeof a ? a.split(this.delimiter) : a.slice(), d = e.call(this, null, c, this.listenerTree, 0), f = 0; f < d.length; f++) {
                    var g = d[f];
                    g._listeners = null
                } else {
                    if (!this._events[a]) return this;
                    this._events[a] = null
                }
            return this
        }, d.prototype.listeners = function(a) {
            if (this.wildcard) {
                var c = [],
                    d = "string" == typeof a ? a.split(this.delimiter) : a.slice();
                return e.call(this, c, d, this.listenerTree, 0), c
            }
            return this._events || b.call(this), this._events[a] || (this._events[a] = []), g(this._events[a]) || (this._events[a] = [this._events[a]]), this._events[a]
        }, d.prototype.listenersAny = function() {
            return this._all ? this._all : []
        }, "function" == typeof define && define.amd ? define(function() {
            return d
        }) : a.EventEmitter2 = d
    }("undefined" != typeof process && "undefined" != typeof process.title && "undefined" != typeof exports ? exports : window),
    function() {
        var ikrpanojsInterface, __slice = [].slice,
            __hasProp = {}.hasOwnProperty,
            __extends = function(a, b) {
                function c() {
                    this.constructor = a
                }
                for (var d in b) __hasProp.call(b, d) && (a[d] = b[d]);
                return c.prototype = b.prototype, a.prototype = new c, a.__super__ = b.prototype, a
            };
        ikrpanojsInterface = function(_, errorHandler, warningFunction, krpanoObjectID) {
            var Buffer, Hotspot, KR_PREXMLREADY, KR_READY, Layer, Node, NodeWithStyle, action, attributeString, callbacks, canGetKrVar, checkReady, commandBuffer, continueQueueExecution, convertActionToKrpano, convertArgsToKrpano, createAction, createCallback, createContainer, createEvents, createExecuteAction, createHotspot, createImage, createLayer, createNode, createObject, createPlugin, eachHotspot, eachLayer, eachNode, emitCoordsEvent, eventEmitter, execInterval, execOnPreXmlReady, execOnReady, executeActionWithArgList, executeQueue, executeQueued, executeQueuedTimer, functionInNodeString, generateUniqueNameForNode, getActionAndArguments, getCallbackID, getContextByName, getElementsProperties, getFunctionByName, getMultiple, handleException, hotspot, ijs, ijsWithArgList, ijs_emit, ijs_eval, ijs_executeCallback, ijs_executeCallbackWithArgList, ijs_getObjectProperty, ijs_on, initActionsAndEvents, kr, krAdd, krcall, krget, krset, krtrace, layer, nameSeq, node, objects, parseKrAddAttributes, pauseQueueExecution, preXmlReady, previousEmbedPano, publicMethods, qaction, qcreateAction, qcreateContainer, qcreateEvents, qcreateHotspot, qcreateImage, qcreateLayer, qcreateNode, qcreatePlugin, qset, queueActionWithArgList, ready, readyTimer, removeTrailingNulls, showErrorsInJsConsole, showErrorsInKrpano, _ref;
            return null == krpanoObjectID && (krpanoObjectID = "krpanoSWFObject"), KR_READY = !1, KR_PREXMLREADY = !1, null == errorHandler && (errorHandler = function(a, b) {
                return a.stack ? console.error(a.stack) : console.error(b)
            }), null == warningFunction && (warningFunction = function(a) {
                return console.warn(a)
            }), execInterval = function(a, b) {
                return setInterval(b, a)
            }, showErrorsInKrpano = !0, showErrorsInJsConsole = !0, "undefined" != typeof EventEmitter2 && null !== EventEmitter2 && (eventEmitter = new EventEmitter2({
                wildcard: !0,
                maxListeners: 100
            })), kr = function() {
                return document.getElementById(krpanoObjectID)
            }, krtrace = function(a) {
                return kr().call("trace(" + a + ")")
            }, handleException = function(a, b) {
                return errorHandler(a, b)
            }, krcall = function(a) {
                var b;
                try {
                    return kr().call(a)
                } catch (c) {
                    return b = c, handleException(b, 'Failed to call() on krpano. This may be caused by a Flash permissions issue. Error was: "' + b + '"')
                }
            }, krset = function(a, b) {
                var c;
                try {
                    return kr().set(a, null != b ? "" + b : "")
                } catch (d) {
                    return c = d, handleException(c, 'Failed to set() on krpano. This may be caused by a Flash permissions issue. Error was: "' + c + '"')
                }
            }, krget = function(a) {
                var b;
                try {
                    return kr().get(a)
                } catch (c) {
                    return b = c, handleException(b, 'Failed to get() on krpano. This may be caused by a Flash permissions issue. Error was: "' + b + '"')
                }
            }, getMultiple = function(a) {
                var b, c, d, e, f;
                try {
                    if (null != kr().getMultiple) return kr().getMultiple(a);
                    for (f = [], d = 0, e = a.length; e > d; d++) c = a[d], f.push(kr().get(c));
                    return f
                } catch (g) {
                    return b = g, handleException(b, 'Failed to getMultiple() on krpano. This may be caused by a Flash permissions issue. Error was: "' + b + '"')
                }
            }, getElementsProperties = function(a, b) {
                var c, d;
                try {
                    return null != kr().getElementsProperties ? (d = kr().getElementsProperties(a, b), _.zipObject(d[0], d[1])) : (d = {}, _.each(a, function(a) {
                        return _.each(b, function(b) {
                            var c, e;
                            return e = "" + a + "." + b, c = kr().get(e), null != c ? d[e] = c : void 0
                        })
                    }), d)
                } catch (e) {
                    return c = e, handleException(c, 'Failed to getElementsProperties() on krpano. This may be caused by a Flash permissions issue. Error was: "' + c + '"')
                }
            }, getActionAndArguments = function(a) {
                var b, c;
                return b = a[0], c = a[1], _.isArray(b) ? [_.first(b), _.rest(b)] : "string" == typeof b && _.isArray(c) ? [b, c] : [b, _.rest(a)]
            }, action = function() {
                var a, b, c, d;
                return b = 1 <= arguments.length ? __slice.call(arguments, 0) : [], d = getActionAndArguments(b), a = d[0], c = d[1], executeActionWithArgList(a, c)
            }, executeActionWithArgList = function(a, b) {
                var c;
                return c = convertActionToKrpano(a, b), krcall(c)
            }, commandBuffer = [], executeQueuedTimer = execInterval(10, function() {
                return executeQueued(500)
            }), executeQueue = !0, pauseQueueExecution = function() {
                return executeQueue = !1
            }, continueQueueExecution = function() {
                return executeQueue = !0
            }, qaction = function() {
                var a, b, c, d;
                return b = 1 <= arguments.length ? __slice.call(arguments, 0) : [], d = getActionAndArguments(b), a = d[0], c = d[1], queueActionWithArgList(a, c)
            }, queueActionWithArgList = function(a, b) {
                var c;
                return c = convertActionToKrpano(a, b), commandBuffer.push(c)
            }, qset = function(a, b) {
                return qaction("set", a, null != b ? "" + b : "")
            }, executeQueued = function(a) {
                var b, c;
                return executeQueue && ready() && (b = commandBuffer.splice(0, a), b.length > 0) ? (c = b.join(";") + ";", krcall(c)) : void 0
            }, convertActionToKrpano = function(a, b) {
                return b = convertArgsToKrpano(b), a + "(" + b.join(",") + ")"
            }, convertArgsToKrpano = function(a) {
                return _.map(a, function(a) {
                    return _.isFunction(a) ? createCallback(a) : !_.isObject(a) || _.isFunction(a) || _.isArray(a) ? '"' + a + '"' : createObject(a)
                })
            }, removeTrailingNulls = function(a) {
                for (;
                    "null" === _.last(a);) a = _.initial(a, 1);
                return a
            }, attributeString = function(a, b, c) {
                return a + "[" + b + "]." + c
            }, functionInNodeString = function(a, b, c, d) {
                return convertActionToKrpano(attributeString(a, b, c), d)
            }, ijsWithArgList = function(a, b, c) {
                var d, e, f, g, h, i, j;
                try {
                    if (i = b.split("."), h = i.pop(), e = i.join("."), d = getContextByName(i), !d) throw 'Context "' + e + '" does not exist';
                    if (g = d[h], !g) throw "Function does not exist";
                    if (j = g.apply(d, c), a && void 0 !== j) return krset(a, j);
                    if (a && "ijs_null" !== a) return warningFunction('Unable to set krVar "' + a + '": No result')
                } catch (k) {
                    return f = k, handleException(f, 'Error calling javascript function "' + b + '": ' + f)
                }
            }, ijs = function(a, b) {
                var c, d;
                try {
                    return c = Array.prototype.slice.call(arguments, 2, arguments.length), c = removeTrailingNulls(c), ijsWithArgList(a, b, c)
                } catch (e) {
                    return d = e, handleException(d, 'Error calling javascript function "' + b + '": ' + d)
                }
            }, ijs_eval = function(krVar, jsCode) {
                var err, result;
                try {
                    if (result = eval(jsCode), krVar && void 0 !== result) return krset(krVar, result);
                    if (krVar && "ijs_null" !== krVar) return warningFunction('Unable to set krVar "' + krVar + '": No result')
                } catch (_error) {
                    return err = _error, handleException(err, 'Error evaluating code "' + jsCode + '": ' + err)
                }
            }, Buffer = function() {
                function a(a) {
                    this.max = a, this.items = {}, this.nextID = 1
                }
                return a.prototype.place = function(a) {
                    var b;
                    return b = this.nextID, this.nextID = (this.nextID + 1) % this.max, this.items[b] = a, b
                }, a.prototype.get = function(a) {
                    return this.items[a]
                }, a.prototype.remove = function(a) {
                    return delete this.items[a]
                }, a
            }(), callbacks = new Buffer(1e3), getCallbackID = function() {
                var a, b;
                return a = b, b = (b + 1) % maxCallbacks, a
            }, createCallback = function(a) {
                return callbacks.place(a)
            }, ijs_executeCallbackWithArgList = function(a, b) {
                var c, d;
                if (c = callbacks.get(a), !c) return handleException(d, 'Error executing callback "' + a + "\": Callback doesn't exist");
                try {
                    return c.apply(this, b)
                } catch (e) {
                    return d = e, handleException(d, 'Error executing callback "' + a + '": ' + d)
                }
            }, ijs_executeCallback = function(a) {
                var b, c;
                try {
                    return b = Array.prototype.slice.call(arguments, 1, arguments.length), ijs_executeCallbackWithArgList(a, b)
                } catch (d) {
                    return c = d, handleException(c, "Error executing callback " + a + ": " + c)
                }
            }, objects = new Buffer(1e3), createObject = function(a) {
                return objects.place(a)
            }, ijs_getObjectProperty = function() {
                var a, b, c;
                return b = arguments[0], c = 2 <= arguments.length ? __slice.call(arguments, 1) : [], a = objects.get(b), _.each(c, function(b) {
                    return a = a[b]
                }), a
            }, ijs_on = function(a, b, c) {
                var d, e, f;
                try {
                    if (f = a.split("."), d = getContextByName(f), !d) throw 'Unexisting context "' + a + '"';
                    if (!d.on) throw 'Context "' + a + "\" doesn't have an event handler";
                    return d.on(b, createExecuteAction(c))
                } catch (g) {
                    return e = g, handleException(e, 'Error binding to event "' + b + '": ' + e)
                }
            }, ijs_emit = function(a) {
                var b, c;
                try {
                    return b = removeTrailingNulls(arguments), eventEmitter.emit.apply(eventEmitter, b)
                } catch (d) {
                    return c = d, handleException(c, 'Error emitting event "' + a + '": ' + c)
                }
            }, createExecuteAction = function(a) {
                return function() {
                    return executeActionWithArgList(a, arguments)
                }
            }, getContextByName = function(a) {
                var b, c, d, e;
                for (b = window, d = 0, e = a.length; e > d; d++)
                    if (c = a[d], b = b[c], !b) return null;
                return b
            }, getFunctionByName = function(a) {
                var b, c, d;
                return d = a.split("."), c = d.pop(), b = getContextByName(d), b[c]
            }, Node = function() {
                function a(a, b) {
                    this.type = a, this.name = b
                }
                return a.prototype.get = function(a) {
                    return krget(this.attribute(a))
                }, a.prototype.set = function() {
                    var a, b, c, d, e;
                    a = 1 <= arguments.length ? __slice.call(arguments, 0) : [], d = this.parseSetArgs(a), e = [];
                    for (b in d) c = d[b], e.push(this.setAttribute(b, c));
                    return e
                }, a.prototype.qset = function() {
                    var a, b, c, d, e;
                    a = 1 <= arguments.length ? __slice.call(arguments, 0) : [], d = this.parseSetArgs(a), e = [];
                    for (b in d) c = d[b], e.push(this.setAttribute(b, c, !0));
                    return e
                }, a.prototype.attribute = function(a) {
                    return attributeString(this.type, this.name, a)
                }, a.prototype.attributes = function(a) {
                    var b, c, d, e;
                    for (e = [], c = 0, d = a.length; d > c; c++) b = a[c], e.push(this.attribute(b));
                    return e
                }, a.prototype.getMultiple = function(a) {
                    var b, c, d, e;
                    for (e = [], c = 0, d = a.length; d > c; c++) b = a[c], e.push(this.get(b));
                    return e
                }, a.prototype.setAttribute = function(a, b, c) {
                    return null == c && (c = !1), c ? qset(this.attribute(a), b) : krset(this.attribute(a), b)
                }, a.prototype.parseSetArgs = function(a) {
                    var b, c, d;
                    return b = a[0], c = a[1], "string" == typeof b ? (d = {}, d[b] = c, d) : "object" == typeof b ? b : void 0
                }, a.prototype.parseActionArgs = function(a) {
                    var b, c, d, e;
                    return e = getActionAndArguments(a), c = e[0], b = e[1], d = this.attribute(c), [d, b]
                }, a.prototype.action = function() {
                    var a;
                    return a = 1 <= arguments.length ? __slice.call(arguments, 0) : [], action(this.parseActionArgs(a)[0], this.parseActionArgs(a)[1])
                }, a.prototype.qaction = function() {
                    var a;
                    return a = 1 <= arguments.length ? __slice.call(arguments, 0) : [], qaction(this.parseActionArgs(a)[0], this.parseActionArgs(a)[1])
                }, a
            }(), node = function(a, b) {
                return new Node(a, b)
            }, eachNode = function(a, b) {
                var c, d, e;
                return c = 1e3, e = function() {
                    switch (a) {
                        case "layer":
                            return function(a) {
                                return new Layer(a)
                            };
                        case "hotspot":
                            return function(a) {
                                return new Hotspot(a)
                            };
                        default:
                            return function(b) {
                                return new Node(a, b)
                            }
                    }
                }(), d = function() {
                    var b;
                    return (b = function(d, e) {
                        var f, g, h, i;
                        return d.length > 0 && null == d[d.length - 1] ? d : (f = e * c, g = f + (c - 1), h = getMultiple(_.map(function() {
                            i = [];
                            for (var a = f; g >= f ? g >= a : a >= g; g >= f ? a++ : a--) i.push(a);
                            return i
                        }.apply(this), function(b) {
                            return attributeString(a, b, "name")
                        })), b(d.concat(h), ++e))
                    })([], 0)
                }, _.map(_.compact(d()), function(a) {
                    return b(e(a))
                })
            }, NodeWithStyle = function(a) {
                function b() {
                    return _ref = b.__super__.constructor.apply(this, arguments)
                }
                return __extends(b, a), b.prototype.set = function() {
                    var a, b, c, d, e;
                    a = 1 <= arguments.length ? __slice.call(arguments, 0) : [], d = this.parseSetArgs(a), e = [];
                    for (b in d) c = d[b], e.push(this.setAttributeOrLoadStyle(b, c));
                    return e
                }, b.prototype.qset = function() {
                    var a, b, c, d, e;
                    a = 1 <= arguments.length ? __slice.call(arguments, 0) : [], d = this.parseSetArgs(a), e = [];
                    for (b in d) c = d[b], e.push(this.setAttributeOrLoadStyle(b, c, !0));
                    return e
                }, b.prototype.setAttributeOrLoadStyle = function(a, b, c) {
                    switch (null == c && (c = !1), a) {
                        case "style":
                            return c ? qaction("ijs_loadstyleAndRefreshCss", this.type, this.name, b) : action("ijs_loadstyleAndRefreshCss", this.type, this.name, b);
                        default:
                            return this.setAttribute(a, b, c)
                    }
                }, b
            }(Node), Layer = function(a) {
                function b(a) {
                    this.name = a, b.__super__.constructor.call(this, "layer", this.name)
                }
                return __extends(b, a), b.prototype.remove = function() {
                    return action("removelayer", this.name)
                }, b.prototype.qremove = function() {
                    return qaction("removelayer", this.name)
                }, b
            }(NodeWithStyle), Hotspot = function(a) {
                function b(a) {
                    this.name = a, b.__super__.constructor.call(this, "hotspot", this.name)
                }
                return __extends(b, a), b.prototype.remove = function() {
                    return action("removehotspot", this.name)
                }, b.prototype.qremove = function() {
                    return qaction("removehotspot", this.name)
                }, b
            }(NodeWithStyle), layer = function(a) {
                return new Layer(a)
            }, hotspot = function(a) {
                return new Hotspot(a)
            }, eachLayer = function(a) {
                return eachNode("layer", a)
            }, eachHotspot = function(a) {
                return eachNode("hotspot", a)
            }, nameSeq = 1, generateUniqueNameForNode = function() {
                var a, b;
                return b = (1e10 * Math.random()).toFixed(), a = "ikr-" + nameSeq + "-" + b, nameSeq += 1, a
            }, parseKrAddAttributes = function(a, b) {
                var c, d;
                return "string" == typeof b ? (d = b, c = {}) : null != (null != b ? b.name : void 0) ? (d = b.name, delete b.name, c = b) : (d = generateUniqueNameForNode(a), c = null != b ? b : {}), [d, c]
            }, krAdd = function(a, b, c, d, e, f) {
                var g, h, i, j, k, l;
                null == e && (e = {}), null == f && (f = !1), l = parseKrAddAttributes(a, d), i = l[0], h = l[1], h = _.extend(h, e), c && (f ? qaction(c, i) : action(c, i)), k = b ? new b(i) : new Node(a, i);
                for (g in h) j = h[g], f ? k.qset(g, j) : k.set(g, j);
                return k
            }, createNode = function(a, b) {
                return krAdd(a, null, null, b)
            }, createHotspot = function(a) {
                return krAdd("hotspot", Hotspot, "addhotspot", a)
            }, createLayer = function(a) {
                return krAdd("layer", Layer, "addlayer", a)
            }, createPlugin = function(a) {
                return krAdd("layer", Layer, "addlayer", a, {
                    type: "image"
                })
            }, createImage = createPlugin, createContainer = function(a) {
                return krAdd("layer", Layer, "addlayer", a, {
                    type: "container"
                })
            }, createEvents = function(a) {
                return createNode("events", a)
            }, createAction = function(a, b) {
                return createNode("action", {
                    name: a,
                    content: b
                })
            }, qcreateNode = function(a, b) {
                return krAdd(a, null, null, b, {}, !0)
            }, qcreateHotspot = function(a) {
                return krAdd("hotspot", Hotspot, "addhotspot", a, {}, !0)
            }, qcreateLayer = function(a) {
                return krAdd("layer", Layer, "addlayer", a, {}, !0)
            }, qcreatePlugin = function(a) {
                return krAdd("layer", Layer, "addlayer", a, {
                    type: "image"
                }, !0)
            }, qcreateImage = qcreatePlugin, qcreateContainer = function(a) {
                return krAdd("layer", Layer, "addlayer", a, {
                    type: "container"
                }, !0)
            }, qcreateEvents = function(a) {
                return qcreateNode("events", a)
            }, qcreateAction = function(a, b) {
                return qcreateNode("action", {
                    name: a,
                    content: b
                })
            }, ready = function() {
                return KR_READY
            }, preXmlReady = function() {
                return KR_PREXMLREADY
            }, canGetKrVar = function(a) {
                var b;
                try {
                    return null != kr().get(a)
                } catch (c) {
                    return b = c, !1
                }
            }, window.embedpano ? (previousEmbedPano = window.embedpano, window.embedpano = function(a) {
                var b;
                return b = a.onready, a.onready = function() {
                    return initActionsAndEvents(), KR_PREXMLREADY = !0, eventEmitter.emit("preXmlReady"), "function" == typeof b ? b() : void 0
                }, previousEmbedPano(a)
            }) : console.error("This script requires embedpano() to exist. Please load it after krpano."), checkReady = function() {
                return canGetKrVar("xmlversion") && canGetKrVar("xml.url") ? (clearInterval(readyTimer), KR_READY = !0, eventEmitter.emit("ready")) : void 0
            }, execOnPreXmlReady = function(a) {
                return preXmlReady() ? a() : eventEmitter.on("preXmlReady", function() {
                    return a()
                })
            }, execOnReady = function(a) {
                return ready() ? a() : eventEmitter.on("ready", function() {
                    return a()
                })
            }, readyTimer = execInterval(10, checkReady), emitCoordsEvent = function(a) {
                return "screentosphere(mouse.x, mouse.y, ikrp_ath, ikrp_atv);		 ijs_emit(" + a + ", get(ikrp_ath), get(ikrp_atv), get(mouse.x), get(mouse.y));"
            }, initActionsAndEvents = function() {
                return createAction("ijs", "js(ikrp.ijs(ijs_null,%1,%2,%3,%4,%5,%6,%7,%8,%9,%10,%11,%12));"), createAction("ijs_set", "js(ikrp.ijs(%1,%2,%3,%4,%5,%6,%7,%8,%9,%10,%11,%12,%13));"), createAction("ijs_eval", "js(ikrp.ijs_eval(%1,%2));"), createAction("ijs_executeCallback", "js(ikrp.ijs_executeCallback(%1,%2,%3,%4,%5,%6,%7,%8,%9,%10,%11,%12));"), createAction("ijs_on", "js(ikrp.ijs_on(%1,%2,%3));"), createAction("ijs_emit", "js(ikrp.ijs_emit(%1,%2,%3,%4,%5,%6,%7,%8,%9,%10,%11,%12));"), createAction("ijs_getObjectProperty", "ijs_set(%1, ikrp.ijs_getObjectProperty, %2,%3,%4,%5,%6,%7,%8,%9,%10,%11,%12);"), createAction("ijs_loadstyleAndRefreshCss", "copy(oldcss, %1[%2].css);			%1[%2].loadstyle(%3);			if(style[%3].css, 				if(style[%3].css != oldcss,					copy(%1[%2].css, %1[%2].css)				);			);"), createEvents({
                    keep: !0,
                    onenterfullscreen: "ijs_emit(onenterfullscreen);",
                    onexitfullscreen: "ijs_emit(onexitfullscreen);",
                    onxmlcomplete: "ijs_emit(onxmlcomplete);",
                    onpreviewcomplete: "ijs_emit(onpreviewcomplete);",
                    onloadcomplete: "ijs_emit(onloadcomplete);",
                    onnewpano: "ijs_emit(onnewpano);",
                    onremovepano: "ijs_emit(onremovepano);",
                    onloaderror: "ijs_emit(onloaderror);",
                    onclick: emitCoordsEvent("onclick"),
                    onmousedown: emitCoordsEvent("onmousedown"),
                    onmouseup: emitCoordsEvent("onmouseup"),
                    onresize: "ijs_emit(onresize);"
                })
            }, publicMethods = {
                krpano: kr,
                get: krget,
                getMultiple: getMultiple,
                getElementsProperties: getElementsProperties,
                trace: krtrace,
                ready: ready,
                action: action,
                set: krset,
                qaction: qaction,
                qset: qset,
                pauseQueueExecution: pauseQueueExecution,
                continueQueueExecution: continueQueueExecution,
                execOnReady: execOnReady,
                execOnPreXmlReady: execOnPreXmlReady,
                node: node,
                eachNode: eachNode,
                layer: layer,
                hotspot: hotspot,
                eachLayer: eachLayer,
                eachHotspot: eachHotspot,
                createNode: createNode,
                createHotspot: createHotspot,
                createLayer: createLayer,
                createPlugin: createPlugin,
                createImage: createImage,
                createContainer: createContainer,
                createEvents: createEvents,
                createAction: createAction,
                qcreateNode: qcreateNode,
                qcreateHotspot: qcreateHotspot,
                qcreateLayer: qcreateLayer,
                qcreatePlugin: qcreatePlugin,
                qcreateImage: qcreateImage,
                qcreateContainer: qcreateContainer,
                qcreateEvents: qcreateEvents,
                qcreateAction: qcreateAction,
                ijs: ijs,
                ijs_eval: ijs_eval,
                ijs_executeCallback: ijs_executeCallback,
                ijs_getObjectProperty: ijs_getObjectProperty,
                ijs_on: ijs_on,
                ijs_emit: ijs_emit
            }, null != eventEmitter ? _.extend(eventEmitter, publicMethods) : publicMethods
        }, window.ikrp = ikrpanojsInterface(_)
    }.call(this),
    function(a, b) {
        "object" == typeof exports ? module.exports = b(require("./punycode"), require("./IPv6"), require("./SecondLevelDomains")) : "function" == typeof define && define.amd ? define(["./punycode", "./IPv6", "./SecondLevelDomains"], b) : a.URI = b(a.punycode, a.IPv6, a.SecondLevelDomains, a)
    }(this, function(a, b, c, d) {
        "use strict";

        function e(a, b) {
            return this instanceof e ? (void 0 === a && (a = "undefined" != typeof location ? location.href + "" : ""), this.href(a), void 0 !== b ? this.absoluteTo(b) : this) : new e(a, b)
        }

        function f(a) {
            return a.replace(/([.*+?^=!:${}()|[\]\/\\])/g, "\\$1")
        }

        function g(a) {
            return void 0 === a ? "Undefined" : String(Object.prototype.toString.call(a)).slice(8, -1)
        }

        function h(a) {
            return "Array" === g(a)
        }

        function i(a, b) {
            var c, d, e = {};
            if (h(b))
                for (c = 0, d = b.length; d > c; c++) e[b[c]] = !0;
            else e[b] = !0;
            for (c = 0, d = a.length; d > c; c++) void 0 !== e[a[c]] && (a.splice(c, 1), d--, c--);
            return a
        }

        function j(a, b) {
            var c, d;
            if (h(b)) {
                for (c = 0, d = b.length; d > c; c++)
                    if (!j(a, b[c])) return !1;
                return !0
            }
            var e = g(b);
            for (c = 0, d = a.length; d > c; c++)
                if ("RegExp" === e) {
                    if ("string" == typeof a[c] && a[c].match(b)) return !0
                } else if (a[c] === b) return !0;
            return !1
        }

        function k(a, b) {
            if (!h(a) || !h(b)) return !1;
            if (a.length !== b.length) return !1;
            a.sort(), b.sort();
            for (var c = 0, d = a.length; d > c; c++)
                if (a[c] !== b[c]) return !1;
            return !0
        }

        function l(a) {
            return escape(a)
        }

        function m(a) {
            return encodeURIComponent(a).replace(/[!'()*]/g, l).replace(/\*/g, "%2A")
        }
        var n = d && d.URI;
        e.version = "1.12.0";
        var o = e.prototype,
            p = Object.prototype.hasOwnProperty;
        e._parts = function() {
            return {
                protocol: null,
                username: null,
                password: null,
                hostname: null,
                urn: null,
                port: null,
                path: null,
                query: null,
                fragment: null,
                duplicateQueryParameters: e.duplicateQueryParameters,
                escapeQuerySpace: e.escapeQuerySpace
            }
        }, e.duplicateQueryParameters = !1, e.escapeQuerySpace = !0, e.protocol_expression = /^[a-z][a-z0-9.+-]*$/i, e.idn_expression = /[^a-z0-9\.-]/i, e.punycode_expression = /(xn--)/i, e.ip4_expression = /^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/, e.ip6_expression = /^\s*((([0-9A-Fa-f]{1,4}:){7}([0-9A-Fa-f]{1,4}|:))|(([0-9A-Fa-f]{1,4}:){6}(:[0-9A-Fa-f]{1,4}|((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3})|:))|(([0-9A-Fa-f]{1,4}:){5}(((:[0-9A-Fa-f]{1,4}){1,2})|:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3})|:))|(([0-9A-Fa-f]{1,4}:){4}(((:[0-9A-Fa-f]{1,4}){1,3})|((:[0-9A-Fa-f]{1,4})?:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){3}(((:[0-9A-Fa-f]{1,4}){1,4})|((:[0-9A-Fa-f]{1,4}){0,2}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){2}(((:[0-9A-Fa-f]{1,4}){1,5})|((:[0-9A-Fa-f]{1,4}){0,3}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){1}(((:[0-9A-Fa-f]{1,4}){1,6})|((:[0-9A-Fa-f]{1,4}){0,4}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(:(((:[0-9A-Fa-f]{1,4}){1,7})|((:[0-9A-Fa-f]{1,4}){0,5}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:)))(%.+)?\s*$/, e.find_uri_expression = /\b((?:[a-z][\w-]+:(?:\/{1,3}|[a-z0-9%])|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}\/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:'".,<>?Â«Â»â€œâ€�â€˜â€™]))/gi, e.findUri = {
            start: /\b(?:([a-z][a-z0-9.+-]*:\/\/)|www\.)/gi,
            end: /[\s\r\n]|$/,
            trim: /[`!()\[\]{};:'".,<>?Â«Â»â€œâ€�â€žâ€˜â€™]+$/
        }, e.defaultPorts = {
            http: "80",
            https: "443",
            ftp: "21",
            gopher: "70",
            ws: "80",
            wss: "443"
        }, e.invalid_hostname_characters = /[^a-zA-Z0-9\.-]/, e.domAttributes = {
            a: "href",
            blockquote: "cite",
            link: "href",
            base: "href",
            script: "src",
            form: "action",
            img: "src",
            area: "href",
            iframe: "src",
            embed: "src",
            source: "src",
            track: "src",
            input: "src"
        }, e.getDomAttribute = function(a) {
            if (!a || !a.nodeName) return void 0;
            var b = a.nodeName.toLowerCase();
            return "input" === b && "image" !== a.type ? void 0 : e.domAttributes[b]
        }, e.encode = m, e.decode = decodeURIComponent, e.iso8859 = function() {
            e.encode = escape, e.decode = unescape
        }, e.unicode = function() {
            e.encode = m, e.decode = decodeURIComponent
        }, e.characters = {
            pathname: {
                encode: {
                    expression: /%(24|26|2B|2C|3B|3D|3A|40)/gi,
                    map: {
                        "%24": "$",
                        "%26": "&",
                        "%2B": "+",
                        "%2C": ",",
                        "%3B": ";",
                        "%3D": "=",
                        "%3A": ":",
                        "%40": "@"
                    }
                },
                decode: {
                    expression: /[\/\?#]/g,
                    map: {
                        "/": "%2F",
                        "?": "%3F",
                        "#": "%23"
                    }
                }
            },
            reserved: {
                encode: {
                    expression: /%(21|23|24|26|27|28|29|2A|2B|2C|2F|3A|3B|3D|3F|40|5B|5D)/gi,
                    map: {
                        "%3A": ":",
                        "%2F": "/",
                        "%3F": "?",
                        "%23": "#",
                        "%5B": "[",
                        "%5D": "]",
                        "%40": "@",
                        "%21": "!",
                        "%24": "$",
                        "%26": "&",
                        "%27": "'",
                        "%28": "(",
                        "%29": ")",
                        "%2A": "*",
                        "%2B": "+",
                        "%2C": ",",
                        "%3B": ";",
                        "%3D": "="
                    }
                }
            }
        }, e.encodeQuery = function(a, b) {
            var c = e.encode(a + "");
            return b ? c.replace(/%20/g, "+") : c
        }, e.decodeQuery = function(a, b) {
            a += "";
            try {
                return e.decode(b ? a.replace(/\+/g, "%20") : a)
            } catch (c) {
                return a
            }
        }, e.recodePath = function(a) {
            for (var b = (a + "").split("/"), c = 0, d = b.length; d > c; c++) b[c] = e.encodePathSegment(e.decode(b[c]));
            return b.join("/")
        }, e.decodePath = function(a) {
            for (var b = (a + "").split("/"), c = 0, d = b.length; d > c; c++) b[c] = e.decodePathSegment(b[c]);
            return b.join("/")
        };
        var q, r = {
                encode: "encode",
                decode: "decode"
            },
            s = function(a, b) {
                return function(c) {
                    return e[b](c + "").replace(e.characters[a][b].expression, function(c) {
                        return e.characters[a][b].map[c]
                    })
                }
            };
        for (q in r) e[q + "PathSegment"] = s("pathname", r[q]);
        e.encodeReserved = s("reserved", "encode"), e.parse = function(a, b) {
            var c;
            
            return b || (b = {}), c = a.indexOf("#"), c > -1 && (b.fragment = a.substring(c + 1) || null, a = a.substring(0, c)), c = a.indexOf("?"), c > -1 && (b.query = a.substring(c + 1) || null, a = a.substring(0, c)), "//" === a.substring(0, 2) ? (b.protocol = null, a = a.substring(2), a = e.parseAuthority(a, b)) : (c = a.indexOf(":"), c > -1 && (b.protocol = a.substring(0, c) || null, b.protocol && !b.protocol.match(e.protocol_expression) ? b.protocol = void 0 : "file" === b.protocol ? a = a.substring(c + 3) : "//" === a.substring(c + 1, c + 3) ? (a = a.substring(c + 3), a = e.parseAuthority(a, b)) : (a = a.substring(c + 1), b.urn = !0))), b.path = a, b
        }, e.parseHost = function(a, b) {
            var c, d, e = a.indexOf("/");
            return -1 === e && (e = a.length), "[" === a.charAt(0) ? (c = a.indexOf("]"), b.hostname = a.substring(1, c) || null, b.port = a.substring(c + 2, e) || null) : a.indexOf(":") !== a.lastIndexOf(":") ? (b.hostname = a.substring(0, e) || null, b.port = null) : (d = a.substring(0, e).split(":"), b.hostname = d[0] || null, b.port = d[1] || null), b.hostname && "/" !== a.substring(e).charAt(0) && (e++, a = "/" + a), a.substring(e) || "/"
        }, e.parseAuthority = function(a, b) {
            return a = e.parseUserinfo(a, b), e.parseHost(a, b)
        }, e.parseUserinfo = function(a, b) {
            var c, d = a.indexOf("/"),
                f = d > -1 ? a.lastIndexOf("@", d) : a.indexOf("@");
            return f > -1 && (-1 === d || d > f) ? (c = a.substring(0, f).split(":"), b.username = c[0] ? e.decode(c[0]) : null, c.shift(), b.password = c[0] ? e.decode(c.join(":")) : null, a = a.substring(f + 1)) : (b.username = null, b.password = null), a
        }, e.parseQuery = function(a, b) {
            if (!a) return {};
            if (a = a.replace(/&+/g, "&").replace(/^\?*&*|&+$/g, ""), !a) return {};
            //for (var c, d, f, g = {}, h = a.split("&"), i = h.length, j = 0; i > j; j++) c = h[j].split("="), d = e.decodeQuery(c.shift(), b), f = c.length ? e.decodeQuery(c.join("="), b) : null, g[d] ? ("string" == typeof g[d] && (g[d] = [g[d]]), g[d].push(f)) : g[d] = f;
            for (var c, d, f, g = {}, h = a.split("&"), i = h.length, j = 0; i > j; j++) c = h[j], d = "", f = "";
            
            g.startscene = c;
            //console.log(g.startscene);
            return g
        }, e.build = function(a) {
            var b = "";
            return a.protocol && (b += a.protocol + ":"), a.urn || !b && !a.hostname || (b += "//"), b += e.buildAuthority(a) || "", "string" == typeof a.path && ("/" !== a.path.charAt(0) && "string" == typeof a.hostname && (b += "/"), b += a.path), "string" == typeof a.query && a.query && (b += "?" + a.query), "string" == typeof a.fragment && a.fragment && (b += "#" + a.fragment), b
        }, e.buildHost = function(a) {
            var b = "";
            return a.hostname ? (e.ip6_expression.test(a.hostname) ? b += a.port ? "[" + a.hostname + "]:" + a.port : a.hostname : (b += a.hostname, a.port && (b += ":" + a.port)), b) : ""
        }, e.buildAuthority = function(a) {
            return e.buildUserinfo(a) + e.buildHost(a)
        }, e.buildUserinfo = function(a) {
            var b = "";
            return a.username && (b += e.encode(a.username), a.password && (b += ":" + e.encode(a.password)), b += "@"), b
        }, e.buildQuery = function(a, b, c) {
            var d, f, g, i, j = "";
            for (f in a)
                if (p.call(a, f) && f)
                    if (h(a[f]))
                        for (d = {}, g = 0, i = a[f].length; i > g; g++) void 0 !== a[f][g] && void 0 === d[a[f][g] + ""] && (j += "&" + e.buildQueryParameter(f, a[f][g], c), b !== !0 && (d[a[f][g] + ""] = !0));
                    else void 0 !== a[f] && (j += "&" + e.buildQueryParameter(f, a[f], c));
            return j.substring(1)
        }, e.buildQueryParameter = function(a, b, c) {
            //return e.encodeQuery(a, c) + (null !== b ? "=" + e.encodeQuery(b, c) : "")
        	return (null !== b ? "" + e.encodeQuery(b, c) : "")
        }, e.addQuery = function(a, b, c) {
            if ("object" == typeof b)
                for (var d in b) p.call(b, d) && e.addQuery(a, d, b[d]);
            else {
                if ("string" != typeof b) throw new TypeError("URI.addQuery() accepts an object, string as the name parameter");
                if (void 0 === a[b]) return a[b] = c, void 0;
                "string" == typeof a[b] && (a[b] = [a[b]]), h(c) || (c = [c]), a[b] = a[b].concat(c)
            }
        }, e.removeQuery = function(a, b, c) {
            var d, f, g;
            if (h(b))
                for (d = 0, f = b.length; f > d; d++) a[b[d]] = void 0;
            else if ("object" == typeof b)
                for (g in b) p.call(b, g) && e.removeQuery(a, g, b[g]);
            else {
                if ("string" != typeof b) throw new TypeError("URI.addQuery() accepts an object, string as the first parameter");
                void 0 !== c ? a[b] === c ? a[b] = void 0 : h(a[b]) && (a[b] = i(a[b], c)) : a[b] = void 0
            }
        }, e.hasQuery = function(a, b, c, d) {
            if ("object" == typeof b) {
                for (var f in b)
                    if (p.call(b, f) && !e.hasQuery(a, f, b[f])) return !1;
                return !0
            }
            if ("string" != typeof b) throw new TypeError("URI.hasQuery() accepts an object, string as the name parameter");
            switch (g(c)) {
                case "Undefined":
                    return b in a;
                case "Boolean":
                    var i = Boolean(h(a[b]) ? a[b].length : a[b]);
                    return c === i;
                case "Function":
                    return !!c(a[b], b, a);
                case "Array":
                    if (!h(a[b])) return !1;
                    var l = d ? j : k;
                    return l(a[b], c);
                case "RegExp":
                    return h(a[b]) ? d ? j(a[b], c) : !1 : Boolean(a[b] && a[b].match(c));
                case "Number":
                    c = String(c);
                case "String":
                    return h(a[b]) ? d ? j(a[b], c) : !1 : a[b] === c;
                default:
                    throw new TypeError("URI.hasQuery() accepts undefined, boolean, string, number, RegExp, Function as the value parameter")
            }
        }, e.commonPath = function(a, b) {
            var c, d = Math.min(a.length, b.length);
            for (c = 0; d > c; c++)
                if (a.charAt(c) !== b.charAt(c)) {
                    c--;
                    break
                }
            return 1 > c ? a.charAt(0) === b.charAt(0) && "/" === a.charAt(0) ? "/" : "" : (("/" !== a.charAt(c) || "/" !== b.charAt(c)) && (c = a.substring(0, c).lastIndexOf("/")), a.substring(0, c + 1))
        }, e.withinString = function(a, b, c) {
            c || (c = {});
            var d = c.start || e.findUri.start,
                f = c.end || e.findUri.end,
                g = c.trim || e.findUri.trim,
                h = /[a-z0-9-]=["']?$/i;
            for (d.lastIndex = 0;;) {
                var i = d.exec(a);
                if (!i) break;
                var j = i.index;
                if (c.ignoreHtml) {
                    var k = a.slice(Math.max(j - 3, 0), j);
                    if (k && h.test(k)) continue
                }
                var l = j + a.slice(j).search(f),
                    m = a.slice(j, l).replace(g, "");
                if (!c.ignore || !c.ignore.test(m)) {
                    l = j + m.length;
                    var n = b(m, j, l, a);
                    a = a.slice(0, j) + n + a.slice(l), d.lastIndex = j + n.length
                }
            }
            return d.lastIndex = 0, a
        }, e.ensureValidHostname = function(b) {
            if (b.match(e.invalid_hostname_characters)) {
                if (!a) throw new TypeError("Hostname '" + b + "' contains characters other than [A-Z0-9.-] and Punycode.js is not available");
                if (a.toASCII(b).match(e.invalid_hostname_characters)) throw new TypeError("Hostname '" + b + "' contains characters other than [A-Z0-9.-]")
            }
        }, e.noConflict = function(a) {
            if (a) {
                var c = {
                    URI: this.noConflict()
                };
                return URITemplate && "function" == typeof URITemplate.noConflict && (c.URITemplate = URITemplate.noConflict()), b && "function" == typeof b.noConflict && (c.IPv6 = b.noConflict()), SecondLevelDomains && "function" == typeof SecondLevelDomains.noConflict && (c.SecondLevelDomains = SecondLevelDomains.noConflict()), c
            }
            return d.URI === this && (d.URI = n), this
        }, o.build = function(a) {
            return a === !0 ? this._deferred_build = !0 : (void 0 === a || this._deferred_build) && (this._string = e.build(this._parts), this._deferred_build = !1), this
        }, o.clone = function() {
            return new e(this)
        }, o.valueOf = o.toString = function() {
            return this.build(!1)._string
        }, r = {
            protocol: "protocol",
            username: "username",
            password: "password",
            hostname: "hostname",
            port: "port"
        }, s = function(a) {
            return function(b, c) {
                return void 0 === b ? this._parts[a] || "" : (this._parts[a] = b || null, this.build(!c), this)
            }
        };
        for (q in r) o[q] = s(r[q]);
        r = {
            query: "?",
            fragment: "#"
        }, s = function(a, b) {
            return function(c, d) {
                return void 0 === c ? this._parts[a] || "" : (null !== c && (c += "", c.charAt(0) === b && (c = c.substring(1))), this._parts[a] = c, this.build(!d), this)
            }
        };
        for (q in r) o[q] = s(q, r[q]);
        r = {
            search: ["?", "query"],
            hash: ["#", "fragment"]
        }, s = function(a, b) {
            return function(c, d) {
                var e = this[a](c, d);
                return "string" == typeof e && e.length ? b + e : e
            }
        };
        for (q in r) o[q] = s(r[q][1], r[q][0]);
        o.pathname = function(a, b) {
            if (void 0 === a || a === !0) {
                var c = this._parts.path || (this._parts.hostname ? "/" : "");
                return a ? e.decodePath(c) : c
            }
            return this._parts.path = a ? e.recodePath(a) : "/", this.build(!b), this
        }, o.path = o.pathname, o.href = function(a, b) {
            var c;
            if (void 0 === a) return this.toString();
            this._string = "", this._parts = e._parts();
            var d = a instanceof e,
                f = "object" == typeof a && (a.hostname || a.path || a.pathname);
            if (a.nodeName) {
                var g = e.getDomAttribute(a);
                a = a[g] || "", f = !1
            }
            if (!d && f && void 0 !== a.pathname && (a = a.toString()), "string" == typeof a) this._parts = e.parse(a, this._parts);
            else {
                if (!d && !f) throw new TypeError("invalid input");
                var h = d ? a._parts : a;
                for (c in h) p.call(this._parts, c) && (this._parts[c] = h[c])
            }
            return this.build(!b), this
        }, o.is = function(a) {
            var b = !1,
                d = !1,
                f = !1,
                g = !1,
                h = !1,
                i = !1,
                j = !1,
                k = !this._parts.urn;
            switch (this._parts.hostname && (k = !1, d = e.ip4_expression.test(this._parts.hostname), f = e.ip6_expression.test(this._parts.hostname), b = d || f, g = !b, h = g && c && c.has(this._parts.hostname), i = g && e.idn_expression.test(this._parts.hostname), j = g && e.punycode_expression.test(this._parts.hostname)), a.toLowerCase()) {
                case "relative":
                    return k;
                case "absolute":
                    return !k;
                case "domain":
                case "name":
                    return g;
                case "sld":
                    return h;
                case "ip":
                    return b;
                case "ip4":
                case "ipv4":
                case "inet4":
                    return d;
                case "ip6":
                case "ipv6":
                case "inet6":
                    return f;
                case "idn":
                    return i;
                case "url":
                    return !this._parts.urn;
                case "urn":
                    return !!this._parts.urn;
                case "punycode":
                    return j
            }
            return null
        };
        var t = o.protocol,
            u = o.port,
            v = o.hostname;
        o.protocol = function(a, b) {
            if (void 0 !== a && a && (a = a.replace(/:(\/\/)?$/, ""), !a.match(e.protocol_expression))) throw new TypeError("Protocol '" + a + "' contains characters other than [A-Z0-9.+-] or doesn't start with [A-Z]");
            return t.call(this, a, b)
        }, o.scheme = o.protocol, o.port = function(a, b) {
            if (this._parts.urn) return void 0 === a ? "" : this;
            if (void 0 !== a && (0 === a && (a = null), a && (a += "", ":" === a.charAt(0) && (a = a.substring(1)), a.match(/[^0-9]/)))) throw new TypeError("Port '" + a + "' contains characters other than [0-9]");
            return u.call(this, a, b)
        }, o.hostname = function(a, b) {
            if (this._parts.urn) return void 0 === a ? "" : this;
            if (void 0 !== a) {
                var c = {};
                e.parseHost(a, c), a = c.hostname
            }
            return v.call(this, a, b)
        }, o.host = function(a, b) {
            return this._parts.urn ? void 0 === a ? "" : this : void 0 === a ? this._parts.hostname ? e.buildHost(this._parts) : "" : (e.parseHost(a, this._parts), this.build(!b), this)
        }, o.authority = function(a, b) {
            return this._parts.urn ? void 0 === a ? "" : this : void 0 === a ? this._parts.hostname ? e.buildAuthority(this._parts) : "" : (e.parseAuthority(a, this._parts), this.build(!b), this)
        }, o.userinfo = function(a, b) {
            if (this._parts.urn) return void 0 === a ? "" : this;
            if (void 0 === a) {
                if (!this._parts.username) return "";
                var c = e.buildUserinfo(this._parts);
                return c.substring(0, c.length - 1)
            }
            return "@" !== a[a.length - 1] && (a += "@"), e.parseUserinfo(a, this._parts), this.build(!b), this
        }, o.resource = function(a, b) {
            var c;
            return void 0 === a ? this.path() + this.search() + this.hash() : (c = e.parse(a), this._parts.path = c.path, this._parts.query = c.query, this._parts.fragment = c.fragment, this.build(!b), this)
        }, o.subdomain = function(a, b) {
            if (this._parts.urn) return void 0 === a ? "" : this;
            if (void 0 === a) {
                if (!this._parts.hostname || this.is("IP")) return "";
                var c = this._parts.hostname.length - this.domain().length - 1;
                return this._parts.hostname.substring(0, c) || ""
            }
            var d = this._parts.hostname.length - this.domain().length,
                g = this._parts.hostname.substring(0, d),
                h = new RegExp("^" + f(g));
            return a && "." !== a.charAt(a.length - 1) && (a += "."), a && e.ensureValidHostname(a), this._parts.hostname = this._parts.hostname.replace(h, a), this.build(!b), this
        }, o.domain = function(a, b) {
            if (this._parts.urn) return void 0 === a ? "" : this;
            if ("boolean" == typeof a && (b = a, a = void 0), void 0 === a) {
                if (!this._parts.hostname || this.is("IP")) return "";
                var c = this._parts.hostname.match(/\./g);
                if (c && c.length < 2) return this._parts.hostname;
                var d = this._parts.hostname.length - this.tld(b).length - 1;
                return d = this._parts.hostname.lastIndexOf(".", d - 1) + 1, this._parts.hostname.substring(d) || ""
            }
            if (!a) throw new TypeError("cannot set domain empty");
            if (e.ensureValidHostname(a), !this._parts.hostname || this.is("IP")) this._parts.hostname = a;
            else {
                var g = new RegExp(f(this.domain()) + "$");
                this._parts.hostname = this._parts.hostname.replace(g, a)
            }
            return this.build(!b), this
        }, o.tld = function(a, b) {
            if (this._parts.urn) return void 0 === a ? "" : this;
            if ("boolean" == typeof a && (b = a, a = void 0), void 0 === a) {
                if (!this._parts.hostname || this.is("IP")) return "";
                var d = this._parts.hostname.lastIndexOf("."),
                    e = this._parts.hostname.substring(d + 1);
                return b !== !0 && c && c.list[e.toLowerCase()] ? c.get(this._parts.hostname) || e : e
            }
            var g;
            if (!a) throw new TypeError("cannot set TLD empty");
            if (a.match(/[^a-zA-Z0-9-]/)) {
                if (!c || !c.is(a)) throw new TypeError("TLD '" + a + "' contains characters other than [A-Z0-9]");
                g = new RegExp(f(this.tld()) + "$"), this._parts.hostname = this._parts.hostname.replace(g, a)
            } else {
                if (!this._parts.hostname || this.is("IP")) throw new ReferenceError("cannot set TLD on non-domain host");
                g = new RegExp(f(this.tld()) + "$"), this._parts.hostname = this._parts.hostname.replace(g, a)
            }
            return this.build(!b), this
        }, o.directory = function(a, b) {
            if (this._parts.urn) return void 0 === a ? "" : this;
            if (void 0 === a || a === !0) {
                if (!this._parts.path && !this._parts.hostname) return "";
                if ("/" === this._parts.path) return "/";
                var c = this._parts.path.length - this.filename().length - 1,
                    d = this._parts.path.substring(0, c) || (this._parts.hostname ? "/" : "");
                return a ? e.decodePath(d) : d
            }
            var g = this._parts.path.length - this.filename().length,
                h = this._parts.path.substring(0, g),
                i = new RegExp("^" + f(h));
            return this.is("relative") || (a || (a = "/"), "/" !== a.charAt(0) && (a = "/" + a)), a && "/" !== a.charAt(a.length - 1) && (a += "/"), a = e.recodePath(a), this._parts.path = this._parts.path.replace(i, a), this.build(!b), this
        }, o.filename = function(a, b) {
            if (this._parts.urn) return void 0 === a ? "" : this;
            if (void 0 === a || a === !0) {
                if (!this._parts.path || "/" === this._parts.path) return "";
                var c = this._parts.path.lastIndexOf("/"),
                    d = this._parts.path.substring(c + 1);
                return a ? e.decodePathSegment(d) : d
            }
            var g = !1;
            "/" === a.charAt(0) && (a = a.substring(1)), a.match(/\.?\//) && (g = !0);
            var h = new RegExp(f(this.filename()) + "$");
            return a = e.recodePath(a), this._parts.path = this._parts.path.replace(h, a), g ? this.normalizePath(b) : this.build(!b), this
        }, o.suffix = function(a, b) {
            if (this._parts.urn) return void 0 === a ? "" : this;
            if (void 0 === a || a === !0) {
                if (!this._parts.path || "/" === this._parts.path) return "";
                var c, d, g = this.filename(),
                    h = g.lastIndexOf(".");
                return -1 === h ? "" : (c = g.substring(h + 1), d = /^[a-z0-9%]+$/i.test(c) ? c : "", a ? e.decodePathSegment(d) : d)
            }
            "." === a.charAt(0) && (a = a.substring(1));
            var i, j = this.suffix();
            if (j) i = a ? new RegExp(f(j) + "$") : new RegExp(f("." + j) + "$");
            else {
                if (!a) return this;
                this._parts.path += "." + e.recodePath(a)
            }
            return i && (a = e.recodePath(a), this._parts.path = this._parts.path.replace(i, a)), this.build(!b), this
        }, o.segment = function(a, b, c) {
            var d = this._parts.urn ? ":" : "/",
                e = this.path(),
                f = "/" === e.substring(0, 1),
                g = e.split(d);
            if (void 0 !== a && "number" != typeof a && (c = b, b = a, a = void 0), void 0 !== a && "number" != typeof a) throw new Error("Bad segment '" + a + "', must be 0-based integer");
            if (f && g.shift(), 0 > a && (a = Math.max(g.length + a, 0)), void 0 === b) return void 0 === a ? g : g[a];
            if (null === a || void 0 === g[a])
                if (h(b)) {
                    g = [];
                    for (var i = 0, j = b.length; j > i; i++)(b[i].length || g.length && g[g.length - 1].length) && (g.length && !g[g.length - 1].length && g.pop(), g.push(b[i]))
                } else(b || "string" == typeof b) && ("" === g[g.length - 1] ? g[g.length - 1] = b : g.push(b));
            else b || "string" == typeof b && b.length ? g[a] = b : g.splice(a, 1);
            return f && g.unshift(""), this.path(g.join(d), c)
        }, o.segmentCoded = function(a, b, c) {
            var d, f, g;
            if ("number" != typeof a && (c = b, b = a, a = void 0), void 0 === b) {
                if (d = this.segment(a, b, c), h(d))
                    for (f = 0, g = d.length; g > f; f++) d[f] = e.decode(d[f]);
                else d = void 0 !== d ? e.decode(d) : void 0;
                return d
            }
            if (h(b))
                for (f = 0, g = b.length; g > f; f++) b[f] = e.decode(b[f]);
            else b = "string" == typeof b ? e.encode(b) : b;
            return this.segment(a, b, c)
        };
        var w = o.query;
        return o.query = function(a, b) {
            if (a === !0) return e.parseQuery(this._parts.query, this._parts.escapeQuerySpace);
            if ("function" == typeof a) {
                var c = e.parseQuery(this._parts.query, this._parts.escapeQuerySpace),
                    d = a.call(this, c);
                return this._parts.query = e.buildQuery(d || c, this._parts.duplicateQueryParameters, this._parts.escapeQuerySpace), this.build(!b), this
            }
            return void 0 !== a && "string" != typeof a ? (this._parts.query = e.buildQuery(a, this._parts.duplicateQueryParameters, this._parts.escapeQuerySpace), this.build(!b), this) : w.call(this, a, b)
        }, o.setQuery = function(a, b, c) {
            var d = e.parseQuery(this._parts.query, this._parts.escapeQuerySpace);
            if ("object" == typeof a)
                for (var f in a) p.call(a, f) && (d[f] = a[f]);
            else {
                if ("string" != typeof a) throw new TypeError("URI.addQuery() accepts an object, string as the name parameter");
                d[a] = void 0 !== b ? b : null
            }
            return this._parts.query = e.buildQuery(d, this._parts.duplicateQueryParameters, this._parts.escapeQuerySpace), "string" != typeof a && (c = b), this.build(!c), this
        }, o.addQuery = function(a, b, c) {
            var d = e.parseQuery(this._parts.query, this._parts.escapeQuerySpace);
            return e.addQuery(d, a, void 0 === b ? null : b), this._parts.query = e.buildQuery(d, this._parts.duplicateQueryParameters, this._parts.escapeQuerySpace), "string" != typeof a && (c = b), this.build(!c), this
        }, o.removeQuery = function(a, b, c) {
            var d = e.parseQuery(this._parts.query, this._parts.escapeQuerySpace);
            return e.removeQuery(d, a, b), this._parts.query = e.buildQuery(d, this._parts.duplicateQueryParameters, this._parts.escapeQuerySpace), "string" != typeof a && (c = b), this.build(!c), this
        }, o.hasQuery = function(a, b, c) {
            var d = e.parseQuery(this._parts.query, this._parts.escapeQuerySpace);
            return e.hasQuery(d, a, b, c)
        }, o.setSearch = o.setQuery, o.addSearch = o.addQuery, o.removeSearch = o.removeQuery, o.hasSearch = o.hasQuery, o.normalize = function() {
            return this._parts.urn ? this.normalizeProtocol(!1).normalizeQuery(!1).normalizeFragment(!1).build() : this.normalizeProtocol(!1).normalizeHostname(!1).normalizePort(!1).normalizePath(!1).normalizeQuery(!1).normalizeFragment(!1).build()
        }, o.normalizeProtocol = function(a) {
            return "string" == typeof this._parts.protocol && (this._parts.protocol = this._parts.protocol.toLowerCase(), this.build(!a)), this
        }, o.normalizeHostname = function(c) {
            return this._parts.hostname && (this.is("IDN") && a ? this._parts.hostname = a.toASCII(this._parts.hostname) : this.is("IPv6") && b && (this._parts.hostname = b.best(this._parts.hostname)), this._parts.hostname = this._parts.hostname.toLowerCase(), this.build(!c)), this
        }, o.normalizePort = function(a) {
            return "string" == typeof this._parts.protocol && this._parts.port === e.defaultPorts[this._parts.protocol] && (this._parts.port = null, this.build(!a)), this
        }, o.normalizePath = function(a) {
            if (this._parts.urn) return this;
            if (!this._parts.path || "/" === this._parts.path) return this;
            var b, c, d, f = this._parts.path,
                g = "";
            for ("/" !== f.charAt(0) && (b = !0, f = "/" + f), f = f.replace(/(\/(\.\/)+)|(\/\.$)/g, "/").replace(/\/{2,}/g, "/"), b && (g = f.substring(1).match(/^(\.\.\/)+/) || "", g && (g = g[0]));;) {
                if (c = f.indexOf("/.."), -1 === c) break;
                0 !== c ? (d = f.substring(0, c).lastIndexOf("/"), -1 === d && (d = c), f = f.substring(0, d) + f.substring(c + 3)) : f = f.substring(3)
            }
            return b && this.is("relative") && (f = g + f.substring(1)), f = e.recodePath(f), this._parts.path = f, this.build(!a), this
        }, o.normalizePathname = o.normalizePath, o.normalizeQuery = function(a) {
            return "string" == typeof this._parts.query && (this._parts.query.length ? this.query(e.parseQuery(this._parts.query, this._parts.escapeQuerySpace)) : this._parts.query = null, this.build(!a)), this
        }, o.normalizeFragment = function(a) {
            return this._parts.fragment || (this._parts.fragment = null, this.build(!a)), this
        }, o.normalizeSearch = o.normalizeQuery, o.normalizeHash = o.normalizeFragment, o.iso8859 = function() {
            var a = e.encode,
                b = e.decode;
            return e.encode = escape, e.decode = decodeURIComponent, this.normalize(), e.encode = a, e.decode = b, this
        }, o.unicode = function() {
            var a = e.encode,
                b = e.decode;
            return e.encode = m, e.decode = unescape, this.normalize(), e.encode = a, e.decode = b, this
        }, o.readable = function() {
            var b = this.clone();
            b.username("").password("").normalize();
            var c = "";
            if (b._parts.protocol && (c += b._parts.protocol + "://"), b._parts.hostname && (b.is("punycode") && a ? (c += a.toUnicode(b._parts.hostname), b._parts.port && (c += ":" + b._parts.port)) : c += b.host()), b._parts.hostname && b._parts.path && "/" !== b._parts.path.charAt(0) && (c += "/"), c += b.path(!0), b._parts.query) {
                for (var d = "", f = 0, g = b._parts.query.split("&"), h = g.length; h > f; f++) {
                    var i = (g[f] || "").split("=");
                    d += "&" + e.decodeQuery(i[0], this._parts.escapeQuerySpace).replace(/&/g, "%26"), void 0 !== i[1] && (d += "=" + e.decodeQuery(i[1], this._parts.escapeQuerySpace).replace(/&/g, "%26"))
                }
                c += "?" + d.substring(1)
            }
            return c += e.decodeQuery(b.hash(), !0)
        }, o.absoluteTo = function(a) {
            var b, c, d, f = this.clone(),
                g = ["protocol", "username", "password", "hostname", "port"];
            if (this._parts.urn) throw new Error("URNs do not have any generally defined hierarchical components");
            if (a instanceof e || (a = new e(a)), f._parts.protocol || (f._parts.protocol = a._parts.protocol), this._parts.hostname) return f;
            for (c = 0; d = g[c]; c++) f._parts[d] = a._parts[d];
            return f._parts.path ? ".." === f._parts.path.substring(-2) && (f._parts.path += "/") : (f._parts.path = a._parts.path, f._parts.query || (f._parts.query = a._parts.query)), "/" !== f.path().charAt(0) && (b = a.directory(), f._parts.path = (b ? b + "/" : "") + f._parts.path, f.normalizePath()), f.build(), f
        }, o.relativeTo = function(a) {
            var b, c, d, f, g, h = this.clone().normalize();
            if (h._parts.urn) throw new Error("URNs do not have any generally defined hierarchical components");
            if (a = new e(a).normalize(), b = h._parts, c = a._parts, f = h.path(), g = a.path(), "/" !== f.charAt(0)) throw new Error("URI is already relative");
            if ("/" !== g.charAt(0)) throw new Error("Cannot calculate a URI relative to another relative URI");
            if (b.protocol === c.protocol && (b.protocol = null), b.username !== c.username || b.password !== c.password) return h.build();
            if (null !== b.protocol || null !== b.username || null !== b.password) return h.build();
            if (b.hostname !== c.hostname || b.port !== c.port) return h.build();
            if (b.hostname = null, b.port = null, f === g) return b.path = "", h.build();
            if (d = e.commonPath(h.path(), a.path()), !d) return h.build();
            var i = c.path.substring(d.length).replace(/[^\/]*$/, "").replace(/.*?\//g, "../");
            return b.path = i + b.path.substring(d.length), h.build()
        }, o.equals = function(a) {
            var b, c, d, f = this.clone(),
                g = new e(a),
                i = {},
                j = {},
                l = {};
            if (f.normalize(), g.normalize(), f.toString() === g.toString()) return !0;
            if (b = f.query(), c = g.query(), f.query(""), g.query(""), f.toString() !== g.toString()) return !1;
            if (b.length !== c.length) return !1;
            i = e.parseQuery(b, this._parts.escapeQuerySpace), j = e.parseQuery(c, this._parts.escapeQuerySpace);
            for (d in i)
                if (p.call(i, d)) {
                    if (h(i[d])) {
                        if (!k(i[d], j[d])) return !1
                    } else if (i[d] !== j[d]) return !1;
                    l[d] = !0
                }
            for (d in j)
                if (p.call(j, d) && !l[d]) return !1;
            return !0
        }, o.duplicateQueryParameters = function(a) {
            return this._parts.duplicateQueryParameters = !!a, this
        }, o.escapeQuerySpace = function(a) {
            return this._parts.escapeQuerySpace = !!a, this
        }, e
    }),
    function(a, b) {
        "object" == typeof exports ? module.exports = b(require("./URI")) : "function" == typeof define && define.amd ? define(["./URI"], b) : b(a.URI)
    }(this, function(a) {
        "use strict";
        var b = a.prototype,
            c = b.fragment;
        a.fragmentPrefix = "?";
        var d = a._parts;
        return a._parts = function() {
            var b = d();
            return b.fragmentPrefix = a.fragmentPrefix, b
        }, b.fragmentPrefix = function(a) {
            return this._parts.fragmentPrefix = a, this
        }, b.fragment = function(b, d) {
            var e = this._parts.fragmentPrefix,
                f = this._parts.fragment || "";
            return b === !0 ? f.substring(0, e.length) !== e ? {} : a.parseQuery(f.substring(e.length)) : void 0 !== b && "string" != typeof b ? (this._parts.fragment = e + a.buildQuery(b), this.build(!d), this) : c.call(this, b, d)
        }, b.addFragment = function(b, c, d) {
            var e = this._parts.fragmentPrefix,
                f = a.parseQuery((this._parts.fragment || "").substring(e.length));
            return a.addQuery(f, b, c), this._parts.fragment = e + a.buildQuery(f), "string" != typeof b && (d = c), this.build(!d), this
        }, b.removeFragment = function(b, c, d) {
            var e = this._parts.fragmentPrefix,
                f = a.parseQuery((this._parts.fragment || "").substring(e.length));
            return a.removeQuery(f, b, c), this._parts.fragment = e + a.buildQuery(f), "string" != typeof b && (d = c), this.build(!d), this
        }, b.addHash = b.addFragment, b.removeHash = b.removeFragment, {}
    }),
    function() {
        var a = function(a, b) {
            return function() {
                return a.apply(b, arguments)
            }
        };
        window.JAddress = function() {
            function b() {
                this.autoUpdateUrl = a(this.autoUpdateUrl, this), this.handlePanoChange = a(this.handlePanoChange, this), this.start = a(this.start, this);
                var b = this;
                this.sceneMode(), this.started = !1, ikrp.execOnPreXmlReady(function() {
                    return ikrp.createAction("ja-start", "ijs(jaddress.start, %1, %2, %3)"), ikrp.createAction("ja-autoUpdateUrl", "ijs(jaddress.autoUpdateUrl, %1)"), ikrp.createAction("ja-loadOptions", "ijs(jaddress.loadOptions, %1, %2)"), ikrp.createAction("ja-url", "ijs_set(%1, jaddress.url, %2)")
                }), this.autoUpdateUrlEnabled = !0, ikrp.on("onxmlcomplete", this.handlePanoChange), this.loadFlags = null, this.loadBlend = null, this.ignoreHashChangeEnabled = !1, window.addEventListener("hashchange", function() {
                    return b.ignoreHashChangeEnabled ? void 0 : b.handleHashChange()
                })
            }
            var c, d, e;
            return b.prototype.xmlModeCurrentSceneFun = function() {
                var a;
                if (a = ikrp.get("xml.url"), a.indexOf(".xml") === a.length - 4) return a.substr(0, a.length - 4);
                throw new Error("Default jAddress handler requires XML files to have .xml extension")
            }, b.prototype.xmlModeLoadFun = function(a) {
                return ["loadpano", "" + a + ".xml"]
            }, b.prototype.sceneModeCurrentSceneFun = function() {
                var a, b, c;
                return a = ikrp.get("xml.scene"), b = ikrp.get("scene[" + a + "].name"), c = ikrp.get("scene[" + a + "].urlname"), c || b
            }, b.prototype.sceneModeLoadFun = function(a) {
                var b, c, d, e, f, g;
                return null == a ? ["loadscene", 0] : (b = ikrp.eachNode("scene", _.identity), d = _.map(b, function(a) {

                    return a.get("urlname")
                }), g = _.zip(b, d), f = _.find(g, function(b) {
                    return a === b[1]
                }), e = _.find(g, function(b) {
                    return a === b[0].name
                }), c = f || e, ["loadscene", c[0].name])
            }, b.prototype.sceneMode = function() {
                return this.setLoadFun(this.sceneModeLoadFun), this.setCurrentSceneFun(this.sceneModeCurrentSceneFun)
            }, b.prototype.xmlMode = function() {
                return this.setLoadFun(this.xmlModeLoadFun), this.setCurrentSceneFun(this.xmlModeCurrentSceneFun)
            }, b.prototype.ignoreHashChange = function(a) {
                return this.ignoreHashChangeEnabled = a
            }, d = function(a, b, c) {
                var d;
                return d = [], null != a && d.push("view.hlookat=" + a), null != b && d.push("view.vlookat=" + b), null != c && d.push("view.fov=" + c), d.join("&")
            }, b.prototype.makeLoadVars = d, e = function() {
                return URI(window.location.href).fragmentPrefix("")
            }, b.prototype.start = function(a, b, c) {
                var d = this;
                return ikrp.execOnReady(function() {
                    var f, g, h, i, j, k;
                    return ("false" === (k = null != a ? a.trim() : void 0) || "null" === k || 0 === (null != a ? a.trim().length : void 0)) && (a = null), j = e().fragment(!0), i = j.startscene, f = j.ath, g = j.atv, h = j.fov, d.started = !0, d.loadFun(i || a, f, g, h, b, c)
                })
            }, b.prototype.startFull = function(a) {
                var b = this;
                return ikrp.execOnReady(function() {
                    var c, d, f, g, h;
                    return h = e().fragment(!0), g = h.startscene, c = h.ath, d = h.atv, f = h.fov, b.started = !0, a(g, c, d, f)
                })
            }, b.prototype.handlePanoChange = function() {
                var a, b, c, d;
                return this.autoUpdateUrlEnabled && this.started && (a = this.currentSceneFun(), b = e().removeFragment("startscene").removeFragment("ath").removeFragment("atv").removeFragment("fov"), a && (b.addFragment("s", a), _.size(e().fragment(!0)) > 0 || _.size(b.fragment(!0)) > 0)) ? !history.replaceState || a !== (null != (c = e().fragment(!0)) ? c.startscene : void 0) && (null != (d = e().fragment(!0)) ? d.startscene : void 0) ? window.location.href = b.toString() : history.replaceState(null, null, b.toString()) : void 0
            }, b.prototype.autoUpdateUrl = function(a) {
                return "false" === a && (a = !1), "null" === a && (a = null), this.autoUpdateUrlEnabled = a
            }, b.prototype.handleHashChange = function() {
                var a, b, c, d, f;
                return f = e().fragment(!0), d = f.startscene, a = f.ath, b = f.atv, c = f.fov, d && d !== this.currentSceneFun() ? this.loadFun(d, a, b, c, this.loadFlags, this.loadBlend) : (null != a && ikrp.set("view.hlookat", a), null != b && ikrp.set("view.vlookat", b), null != c ? ikrp.set("view.fov", c) : void 0)
            }, b.prototype.loadOptions = function(a, b) {
                return this.loadFlags = a, this.loadBlend = b
            }, b.prototype.url = function(a) {
                var b;
                return null == a && (a = !1), b = {
                    startscene: this.currentSceneFun()
                }, (a === !0 || "nofov" === a) && (b.ath = ikrp.get("view.hlookat"), b.atv = ikrp.get("view.vlookat")), a === !0 && (b.fov = ikrp.get("view.fov")), URI(window.location.href).fragmentPrefix("").fragment(null).addFragment(b).toString()
            }, c = function(a) {
                return function(b, c, e, f, g, h) {
                    var i, j, k;
                    return k = a(b), i = k[0], j = k[1], ikrp.action(i, j, d(c, e, f), g, h)
                }
            }, b.prototype.setCurrentSceneFun = function(a) {
                return this.currentSceneFun = a
            }, b.prototype.setLoadFun = function(a) {
                return this.loadFun = c(a)
            }, b.prototype.setFullLoadFun = function(a) {
                return this.loadFun = a
            }, b
        }(), window.jaddress = new JAddress
    }.call(this);