<script>
import { Bar } from 'vue-chartjs'

export default {
  extends: Bar,
  props: ['dataWeek', 'options'],
  mounted () {
    this.renderChart(this.dataWeek,{responsive: true, maintainAspectRatio: false})
  }
}
 </script>