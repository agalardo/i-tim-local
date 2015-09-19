/*
 Highcharts JS v2.3.2 (2012-08-31)
 
 (c) 2009-2011 Torstein Hønsi
 
 License: www.highcharts.com/license
 */
(function (h, t) {
    function z(a, b, c) {
        this.init.call(this, a, b, c)
    }
    function A(a, b, c) {
        a.call(this, b, c);
        if (this.chart.polar)
            this.closeSegment = function (a) {
                var b = this.xAxis.center;
                a.push("L", b[0], b[1])
            }, this.closedStacks = !0
    }
    function B(a, b) {
        var c = this.chart, d = this.options.animation, f = this.group, e = this.markerGroup, g = this.xAxis.center, i = c.plotLeft, m = c.plotTop;
        if (c.polar) {
            if (c.renderer.isSVG)
                if (d === !0 && (d = {}), b) {
                    if (f.attrSetters.scaleX = f.attrSetters.scaleY = function (a, b) {
                        this[b] = a;
                        this.scaleX !== t && this.scaleY !==
                                t && this.element.setAttribute("transform", "translate(" + this.translateX + "," + this.translateY + ") scale(" + this.scaleX + "," + this.scaleY + ")");
                        return!1
                    }, c = {translateX: g[0] + i, translateY: g[1] + m, scaleX: 0, scaleY: 0}, f.attr(c), e)
                        e.attrSetters = f.attrSetters, e.attr(c)
                } else
                    c = {translateX: i, translateY: m, scaleX: 1, scaleY: 1}, f.animate(c, d), e && e.animate(c, d), this.animate = null
        } else
            a.call(this, b)
    }
    var p = h.each, u = h.extend, o = h.merge, D = h.map, n = h.pick, v = h.pInt, j = h.getOptions().plotOptions, k = h.seriesTypes, w = h.extendClass, l = h.wrap,
            q = h.Axis, F = h.Tick, y = h.Series, r = k.column.prototype, s = function () {
            };
    u(z.prototype, {init: function (a, b, c) {
            var d = this, f = d.defaultOptions;
            d.chart = b;
            if (b.angular)
                f.background = {};
            d.options = a = o(f, a);
            (a = a.background) && p([].concat(h.splat(a)).reverse(), function (a) {
                var b = a.backgroundColor, a = o(d.defaultBackgroundOptions, a);
                if (b)
                    a.backgroundColor = b;
                a.color = a.backgroundColor;
                c.options.plotBands.unshift(a)
            })
        }, defaultOptions: {center: ["50%", "50%"], size: "85%", startAngle: 0}, defaultBackgroundOptions: {shape: "circle", borderWidth: 1,
            borderColor: "silver", backgroundColor: {linearGradient: {x1: 0, y1: 0, x2: 0, y2: 1}, stops: [[0, "#FFF"], [1, "#DDD"]]}, from: Number.MIN_VALUE, innerRadius: 0, to: Number.MAX_VALUE, outerRadius: "105%"}});
    var x = q.prototype, q = F.prototype, G = {getOffset: s, redraw: function () {
            this.isDirty = !1
        }, render: function () {
            this.isDirty = !1
        }, setScale: s, setCategories: s, setTitle: s}, E = {isRadial: !0, defaultRadialGaugeOptions: {labels: {align: "center", x: 0, y: null}, minorGridLineWidth: 0, minorTickInterval: "auto", minorTickLength: 10, minorTickPosition: "inside",
            minorTickWidth: 1, plotBands: [], tickLength: 10, tickPosition: "inside", tickWidth: 2, title: {rotation: 0}, zIndex: 2}, defaultRadialXOptions: {gridLineWidth: 1, labels: {align: null, distance: 15, x: 0, y: null}, maxPadding: 0, minPadding: 0, plotBands: [], showLastLabel: !1, tickLength: 0}, defaultRadialYOptions: {gridLineInterpolation: "circle", labels: {align: "right", x: -3, y: -2}, plotBands: [], showLastLabel: !1, title: {x: 4, text: null, rotation: 90}}, setOptions: function (a) {
            this.options = o(this.defaultOptions, this.defaultRadialOptions, a)
        }, getOffset: function () {
            x.getOffset.call(this);
            this.chart.axisOffset[this.side] = 0;
            this.center = this.pane.center = k.pie.prototype.getCenter.call(this.pane)
        }, getLinePath: function (a, b) {
            var c = this.center, b = n(b, c[2] / 2 - this.offset);
            return this.chart.renderer.symbols.arc(this.left + c[0], this.top + c[1], b, b, {start: this.startAngleRad, end: this.endAngleRad, open: !0, innerR: 0})
        }, setAxisTranslation: function () {
            x.setAxisTranslation.call(this);
            if (this.center && (this.transA = this.isCircular ? (this.endAngleRad - this.startAngleRad) / (this.max - this.min || 1) : this.center[2] / 2 /
                    (this.max - this.min || 1), this.isXAxis))
                this.minPixelPadding = this.transA * this.minPointOffset + (this.reversed ? (this.endAngleRad - this.startAngleRad) / 4 : 0)
        }, beforeSetTickPositions: function () {
            this.autoConnect && (this.max += this.categories && 1 || this.pointRange || this.closestPointRange)
        }, setAxisSize: function () {
            x.setAxisSize.call(this);
            if (this.center)
                this.len = this.width = this.height = this.isCircular ? this.center[2] * (this.endAngleRad - this.startAngleRad) / 2 : this.center[2] / 2
        }, getPosition: function (a, b) {
            if (!this.isCircular)
                b =
                        this.translate(a), a = this.min;
            return this.postTranslate(this.translate(a), n(b, this.center[2] / 2) - this.offset)
        }, postTranslate: function (a, b) {
            var c = this.chart, d = this.center, a = this.startAngleRad + a;
            return{x: c.plotLeft + d[0] + Math.cos(a) * b, y: c.plotTop + d[1] + Math.sin(a) * b}
        }, getPlotBandPath: function (a, b, c) {
            var d = this.center, f = this.startAngleRad, e = d[2] / 2, g = [n(c.outerRadius, "100%"), c.innerRadius, n(c.thickness, 10)], i = /%$/, m, C = this.isCircular;
            this.options.gridLineInterpolation === "polygon" ? d = this.getPlotLinePath(a).concat(this.getPlotLinePath(b,
                    !0)) : (C || (g[0] = this.translate(a), g[1] = this.translate(b)), g = D(g, function (a) {
                i.test(a) && (a = v(a, 10) * e / 100);
                return a
            }), c.shape === "circle" || !C ? (a = -Math.PI / 2, b = Math.PI * 1.5, m = !0) : (a = f + this.translate(a), b = f + this.translate(b)), d = this.chart.renderer.symbols.arc(this.left + d[0], this.top + d[1], g[0], g[0], {start: a, end: b, innerR: n(g[1], g[0] - g[2]), open: m}));
            return d
        }, getPlotLinePath: function (a, b) {
            var c = this.center, d = this.chart, f = this.getPosition(a), e, g, i;
            this.isCircular ? i = ["M", c[0] + d.plotLeft, c[1] + d.plotTop, "L", f.x,
                f.y] : this.options.gridLineInterpolation === "circle" ? (a = this.translate(a)) && (i = this.getLinePath(0, a)) : (e = d.xAxis[0], i = [], a = this.translate(a), c = e.tickPositions, e.autoConnect && (c = c.concat([c[0]])), b && (c = [].concat(c).reverse()), p(c, function (b, c) {
                g = e.getPosition(b, a);
                i.push(c ? "L" : "M", g.x, g.y)
            }));
            return i
        }, getTitlePosition: function () {
            var a = this.center, b = this.chart, c = this.options.title;
            return{x: b.plotLeft + a[0] + (c.x || 0), y: b.plotTop + a[1] - {high: 0.5, middle: 0.25, low: 0}[c.align] * a[2] + (c.y || 0)}
        }};
    l(x, "init", function (a,
            b, c) {
        var d = this, f = b.angular, e = b.polar, g = c.isX, i = f && g, m;
        if (f) {
            if (u(this, i ? G : E), m = !g)
                this.defaultRadialOptions = this.defaultRadialGaugeOptions
        } else if (e)
            u(this, E), this.defaultRadialOptions = (m = g) ? this.defaultRadialXOptions : o(this.defaultYAxisOptions, this.defaultRadialYOptions);
        a.call(this, b, c);
        if (!i && (f || e)) {
            a = this.options;
            if (!b.panes)
                b.panes = D(h.splat(b.options.pane), function (a) {
                    return new z(a, b, d)
                });
            this.pane = f = b.panes[c.pane || 0];
            e = f.options;
            b.inverted = !1;
            b.options.chart.zoomType = null;
            this.startAngleRad =
                    f = (e.startAngle - 90) * Math.PI / 180;
            this.endAngleRad = e = (n(e.endAngle, e.startAngle + 360) - 90) * Math.PI / 180;
            this.offset = a.offset || 0;
            if ((this.isCircular = m) && c.max === t && e - f === 2 * Math.PI)
                this.autoConnect = !0
        }
    });
    l(q, "getPosition", function (a, b, c, d, f) {
        var e = this.axis;
        return e.getPosition ? e.getPosition(c) : a.call(this, b, c, d, f)
    });
    l(q, "getLabelPosition", function (a, b, c, d, f, e, g, i, m) {
        var h = this.axis, k = e.y, j = e.align, l = (h.translate(this.pos) + h.startAngleRad + Math.PI / 2) / Math.PI * 180;
        h.isRadial ? (a = h.getPosition(this.pos, h.center[2] /
                2 + n(e.distance, -25)), e.rotation === "auto" ? d.attr({rotation: l}) : k === null && (k = v(d.styles.lineHeight) * 0.9 - d.getBBox().height / 2), j === null && (j = h.isCircular ? l > 20 && l < 160 ? "left" : l > 200 && l < 340 ? "right" : "center" : "center", d.attr({align: j})), a.x += e.x, a.y += k) : a = a.call(this, b, c, d, f, e, g, i, m);
        return a
    });
    l(q, "getMarkPath", function (a, b, c, d, f, e, g) {
        var i = this.axis;
        i.isRadial ? (a = i.getPosition(this.pos, i.center[2] / 2 + d), b = ["M", b, c, "L", a.x, a.y]) : b = a.call(this, b, c, d, f, e, g);
        return b
    });
    j.arearange = o(j.area, {lineWidth: 1, marker: null,
        threshold: null, tooltip: {pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.low}</b> - <b>{point.high}</b><br/>'}, trackByArea: !0, dataLabels: {xLow: 0, xHigh: 0, yLow: 16, yHigh: -6}, shadow: !1});
    q = h.extendClass(h.Point, {applyOptions: function (a, b) {
            var c = this.series, d = c.pointArrayMap, f = 0, e = 0, g = d.length;
            if (typeof a === "object" && typeof a.length !== "number")
                u(this, a), this.options = a;
            else if (a.length) {
                if (a.length > g) {
                    if (typeof a[0] === "string")
                        this.name = a[0];
                    else if (typeof a[0] === "number")
                        this.x =
                                a[0];
                    f++
                }
                for (; e < g; )
                    this[d[e++]] = a[f++]
            }
            this.y = this[c.pointValKey];
            if (this.x === t && c)
                this.x = b === t ? c.autoIncrement() : b;
            return this
        }, toYData: function () {
            return[this.low, this.high]
        }});
    k.arearange = h.extendClass(k.area, {type: "arearange", pointArrayMap: ["low", "high"], pointClass: q, pointValKey: "low", translate: function () {
            var a = this.yAxis;
            k.area.prototype.translate.apply(this);
            p(this.points, function (b) {
                if (b.y !== null)
                    b.plotLow = b.plotY, b.plotHigh = a.translate(b.high, 0, 1, 0, 1)
            })
        }, getSegmentPath: function (a) {
            for (var b =
            [], c = a.length, d = y.prototype.getSegmentPath, f; c--; )
                f = a[c], b.push({plotX: f.plotX, plotY: f.plotHigh});
            a = d.call(this, a);
            d = d.call(this, b);
            b = [].concat(a, d);
            d[0] = "L";
            this.areaPath = this.areaPath.concat(a, d);
            return b
        }, drawDataLabels: function () {
            var a = this.data, b = a.length, c, d = [], f = y.prototype, e = this.options.dataLabels, g, i = this.chart.inverted;
            if (e.enabled || this._hasPointLabels) {
                for (c = b; c--; )
                    g = a[c], g.y = g.high, g.plotY = g.plotHigh, d[c] = g.dataLabel, g.dataLabel = g.dataLabelUpper, i ? (e.align = "left", e.x = e.xHigh) : e.y = e.yHigh;
                f.drawDataLabels.apply(this, arguments);
                for (c = b; c--; )
                    g = a[c], g.dataLabelUpper = g.dataLabel, g.dataLabel = d[c], g.y = g.low, g.plotY = g.plotLow, i ? (e.align = "right", e.x = e.xLow) : e.y = e.yLow;
                f.drawDataLabels.apply(this, arguments)
            }
        }, getSymbol: k.column.prototype.getSymbol, drawPoints: s});
    j.areasplinerange = o(j.arearange);
    k.areasplinerange = w(k.arearange, {type: "areasplinerange", getPointSpline: k.spline.prototype.getPointSpline});
    j.columnrange = o(j.column, j.arearange, {lineWidth: 1, pointRange: null});
    k.columnrange = w(k.arearange,
            {type: "columnrange", translate: function () {
                    var a = this.yAxis, b;
                    r.translate.apply(this);
                    p(this.points, function (c) {
                        var d = c.shapeArgs;
                        c.plotHigh = b = a.translate(c.high, 0, 1, 0, 1);
                        c.plotLow = c.plotY;
                        d.y = b;
                        d.height = c.plotY - b;
                        c.trackerArgs = d
                    })
                }, drawGraph: s, pointAttrToOptions: r.pointAttrToOptions, drawPoints: r.drawPoints, drawTracker: r.drawTracker, animate: r.animate});
    j.gauge = o(j.line, {dataLabels: {enabled: !0, y: 30, borderWidth: 1, borderColor: "silver", borderRadius: 3, style: {fontWeight: "bold"}}, dial: {}, pivot: {}, tooltip: {headerFormat: ""},
        showInLegend: !1});
    j = {type: "gauge", pointClass: h.extendClass(h.Point, {setState: function (a) {
                this.state = a
            }}), angular: !0, translate: function () {
            var a = this, b = a.yAxis, c = b.center;
            a.generatePoints();
            p(a.points, function (d) {
                var f = o(a.options.dial, d.dial), e = v(n(f.radius, 80)) * c[2] / 200, g = v(n(f.baseLength, 70)) * e / 100, i = v(n(f.rearLength, 10)) * e / 100, m = f.baseWidth || 3, h = f.topWidth || 1;
                d.shapeType = "path";
                d.shapeArgs = {d: f.path || ["M", -i, -m / 2, "L", g, -m / 2, e, -h / 2, e, h / 2, g, m / 2, -i, m / 2, "z"], translateX: c[0], translateY: c[1], rotation: (b.startAngleRad +
                            b.translate(d.y)) * 180 / Math.PI};
                d.plotX = c[0];
                d.plotY = c[1]
            })
        }, drawPoints: function () {
            var a = this, b = a.yAxis.center, c = a.pivot, d = a.options, f = d.pivot, e = d.dial;
            p(a.points, function (b) {
                var c = b.graphic, d = b.shapeArgs, f = d.d;
                c ? (c.animate(d), d.d = f) : b.graphic = a.chart.renderer[b.shapeType](d).attr({stroke: e.borderColor || "none", "stroke-width": e.borderWidth || 0, fill: e.backgroundColor || "black", rotation: d.rotation}).add(a.group)
            });
            c ? c.animate({cx: b[0], cy: b[1]}) : a.pivot = a.chart.renderer.circle(b[0], b[1], n(f.radius, 5)).attr({"stroke-width": f.borderWidth ||
                0, stroke: f.borderColor || "silver", fill: f.backgroundColor || "black"}).add(a.group)
        }, animate: function () {
            var a = this;
            p(a.points, function (b) {
                var c = b.graphic;
                c && (c.attr({rotation: a.yAxis.startAngleRad * 180 / Math.PI}), c.animate({rotation: b.shapeArgs.rotation}, a.options.animation))
            });
            a.animate = null
        }, render: function () {
            this.group = this.plotGroup("group", "series", this.visible ? "visible" : "hidden", this.options.zIndex, this.chart.seriesGroup);
            k.pie.prototype.render.call(this);
            this.group.clip(this.chart.clipRect)
        }, setData: k.pie.prototype.setData,
        drawTracker: k.column.prototype.drawTracker};
    k.gauge = h.extendClass(k.line, j);
    j = y.prototype;
    w = h.MouseTracker.prototype;
    j.toXY = function (a) {
        var b, c = this.chart;
        b = a.plotX;
        var d = a.plotY;
        a.rectPlotX = b;
        a.rectPlotY = d;
        a.deg = b / Math.PI * 180;
        b = this.xAxis.postTranslate(a.plotX, this.yAxis.len - d);
        a.plotX = a.polarPlotX = b.x - c.plotLeft;
        a.plotY = a.polarPlotY = b.y - c.plotTop
    };
    l(k.area.prototype, "init", A);
    l(k.areaspline.prototype, "init", A);
    l(k.spline.prototype, "getPointSpline", function (a, b, c, d) {
        var f, e, g, i, h, k, j;
        if (this.chart.polar) {
            f =
                    c.plotX;
            e = c.plotY;
            a = b[d - 1];
            g = b[d + 1];
            this.connectEnds && (a || (a = b[b.length - 2]), g || (g = b[1]));
            if (a && g)
                i = a.plotX, h = a.plotY, b = g.plotX, k = g.plotY, i = (1.5 * f + i) / 2.5, h = (1.5 * e + h) / 2.5, g = (1.5 * f + b) / 2.5, j = (1.5 * e + k) / 2.5, b = Math.sqrt(Math.pow(i - f, 2) + Math.pow(h - e, 2)), k = Math.sqrt(Math.pow(g - f, 2) + Math.pow(j - e, 2)), i = Math.atan2(h - e, i - f), h = Math.atan2(j - e, g - f), j = Math.PI / 2 + (i + h) / 2, Math.abs(i - j) > Math.PI / 2 && (j -= Math.PI), i = f + Math.cos(j) * b, h = e + Math.sin(j) * b, g = f + Math.cos(Math.PI + j) * k, j = e + Math.sin(Math.PI + j) * k, c.rightContX = g, c.rightContY =
                        j;
            d ? (c = ["C", a.rightContX || a.plotX, a.rightContY || a.plotY, i || f, h || e, f, e], a.rightContX = a.rightContY = null) : c = ["M", f, e]
        } else
            c = a.call(this, b, c, d);
        return c
    });
    l(j, "translate", function (a) {
        a.call(this);
        if (this.chart.polar && !this.preventPostTranslate)
            for (var a = this.points, b = a.length; b--; )
                this.toXY(a[b])
    });
    l(j, "getSegmentPath", function (a, b) {
        var c = this.points;
        if (this.chart.polar && this.options.connectEnds !== !1 && b[b.length - 1] === c[c.length - 1] && c[0].y !== null)
            this.connectEnds = !0, b = [].concat(b, [c[0]]);
        return a.call(this,
                b)
    });
    l(j, "animate", B);
    l(r, "animate", B);
    l(j, "setTooltipPoints", function (a, b) {
        this.chart.polar && u(this.xAxis, {tooltipLen: 360, tooltipPosName: "deg"});
        return a.call(this, b)
    });
    l(r, "translate", function (a) {
        var b = this.xAxis, c = this.yAxis.len, d = b.center, f = b.startAngleRad, e = this.chart.renderer, g;
        this.preventPostTranslate = !0;
        a.call(this);
        if (b.isRadial) {
            a = this.points;
            for (g = a.length; g--; )
                b = a[g], b.shapeType = "path", b.shapeArgs = {d: e.symbols.arc(d[0], d[1], c - b.plotY, null, {start: f + b.barX, end: f + b.barX + b.pointWidth, innerR: c -
                                n(b.yBottom, c)})}, this.toXY(b)
        }
    });
    l(w, "getIndex", function (a, b) {
        var c, d = this.chart, f;
        d.polar ? (f = d.xAxis[0].center, c = b.chartX - f[0] - d.plotLeft, d = b.chartY - f[1] - d.plotTop, c = 180 - Math.round(Math.atan2(c, d) / Math.PI * 180)) : c = a.call(this, b);
        return c
    });
    l(w, "getMouseCoordinates", function (a, b) {
        var c = this.chart, d = {xAxis: [], yAxis: []};
        c.polar ? p(c.axes, function (a) {
            var e = a.isXAxis, g = a.center, h = b.chartX - g[0] - c.plotLeft, g = b.chartY - g[1] - c.plotTop;
            d[e ? "xAxis" : "yAxis"].push({axis: a, value: a.translate(e ? Math.PI - Math.atan2(h,
                        g) : Math.sqrt(Math.pow(h, 2) + Math.pow(g, 2)), !0)})
        }) : d = a.call(this, b);
        return d
    })
})(Highcharts);
