<template lang="html">
  <div>
    <div>
      <p class="info">Visar resultat för <b>{{ student.username }}</b>. Antalet misslyckade tester: <span class="red">{{ calculateFailedTests }}</span> av {{ student.result.length }}</p>
    </div>
    <div :class="[value.status ? 'resultSuccess' : 'resultError', 'result']" v-for="value in student.result">
      <div class="header">
        <p>{{ value.requirement }}</p>
      </div>
      <div v-if="value.comment">
        <hr>
        <div class="comment">
          <p><b>Kommentar:</b> {{ value.comment }}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { EventBus } from '../main';

export default {
  props: ['student'],

  computed: {
    calculateFailedTests() {
      var failedTests = 0;

      for (var i = 0; i < this.student.result.length; i++) {
        if(!this.student.result[i].status) {
          failedTests++;
        }
      }
      return failedTests;
    }
  }
}
</script>

<style lang="css" scoped>
.result {
  margin-bottom: 0.8em;
  border: 1px solid #dddddd;
  background-color: white;
  box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
}

.resultError {
  border-left: 10px solid #f24f4f;
}

.resultSuccess {
  border-left: 10px solid #73e26f;
}

.info {
  margin: 0.6em 0;
}

.header {
  padding: 0 0.5em;
  text-transform: uppercase;
}

hr {
  width: 95%;
}

.comment {
  padding: 0 0.5em;
  font-size: 0.85em;
}

.red {
  color: #f24f4f;
  font-weight: bold;
}
</style>
