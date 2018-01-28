<template lang="html">
  <div>
    <div>
      <p class="info">Visar resultat för <a class="student-link" :href="'https://fc.lnu.se/~' + student.username" target="_blank">{{ student.username }}</a>. Antalet lyckade tester: <span :class="[calculatePassedTests === student.result.length ? 'success' : 'error']">{{ calculatePassedTests }}</span> av {{ student.result.length }}</p>
    </div>
    <div :class="[value.status ? 'result-success' : 'result-error', 'result']" v-for="value in student.result">
      <div class="header">
        <p>{{ value.requirement }}</p>
      </div>
      <div v-if="value.comment">
        <hr>
        <div class="comment">
          <p><b>Kommentar: </b><span v-html="value.comment"></span></p>
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
    calculatePassedTests() {
      var passedTests = 0;

      for (var i = 0; i < this.student.result.length; i++) {
        if(this.student.result[i].status) {
          passedTests++;
        }
      }
      return passedTests;
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

.result-error {
  border-left: 10px solid #f24f4f;
}

.result-success {
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

.error {
  color: #f24f4f;
  font-weight: bold;
}

.success {
  color: #73e26f;
  font-weight: bold;
}

.student-link {
  color: #9068be;
  font-weight: bold;
  text-decoration: none;
}
</style>
