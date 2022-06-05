<script>
import { Bar, mixins } from 'vue-chartjs';
const { reactiveProp } = mixins;
const horizonalLinePlugin = {
  id: 'horizontalLine',
  afterDraw: function(chartInstance) {

    var yValue;
    var yScale = chartInstance.scales["y-axis-0"];
    var canvas = chartInstance.chart;
    var ctx = canvas.ctx;
    var index;
    var line;
    var style;

    if (chartInstance.options.horizontalLine) {

      for (index = 0; index < chartInstance.options.horizontalLine.length; index++) {
        line = chartInstance.options.horizontalLine[index];

        if (!line.style) {
          style = "#080808";
        } else {
          style = line.style;
        }

        if (line.y) {
          yValue = yScale.getPixelForValue(line.y);
        } else {
          yValue = 0;
        }
        ctx.lineWidth = 3;

        if (yValue) {
          window.chart = chartInstance;
          ctx.beginPath();
          ctx.moveTo(0, yValue);
          ctx.lineTo(canvas.width, yValue);
          ctx.strokeStyle = style;
          ctx.stroke();
        }

        if (line.text) {
          ctx.fillStyle = style;
          ctx.fillText(line.text, 0, yValue + ctx.lineWidth);
        }
      }
      return;
    }
  }
};
export default {
  extends: Bar,
  mixins: [reactiveProp],
  props: ['options'],
  beforeMount () {
    this.addPlugin(horizonalLinePlugin)
  },
  mounted () {
    this.renderChart(this.chartdata, this.options)
  }
}

</script>
