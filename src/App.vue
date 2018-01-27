<template>
  <div id="app">
    <transition name="fade1">
      <Info v-if="!isExecuting" :firstExecute="firstExecute"></Info>
    </transition>

    <transition name="fade2">
      <Search v-show="!isExecuting"></Search>
    </transition>

    <transition name="fade3">
      <Result v-if="student" :student="student"></Result>
    </transition>

    <Loader v-show="isExecuting"></Loader>
  </div>
</template>

<script>
import { EventBus } from './main'
import Info from './components/Info.vue'
import Search from './components/Search.vue'
import Result from './components/Result.vue'
import Loader from './components/Loader.vue'

export default {
  name: 'app',
  components: { Info, Search, Result, Loader },
  data() {
    return {
      isExecuting: false,
      firstExecute: true,
      student: ''
    }
  },

  created() {
    EventBus.$on('isExecuting', (data) => {
      this.isExecuting = data;
      this.firstExecute = false;
    }),

    EventBus.$on('studentDataUpdated', (data) => {
      this.student = data;
    });
  }
}
</script>

<style>
@import url('https://fonts.googleapis.com/css?family=Open+Sans');

body {
  background-color: #e1e8f0;
  font-family: 'Open Sans', sans-serif;
  font-size: 1.2em;
  overflow-y: scroll;
}

#app {
  padding-right: 7px;
  padding-left: 7px;
  max-width: 790px;
  margin: 0 auto;
}

.center {
  text-align: center;
}

.fade1-enter-active, .fade2-enter-active, .fade3-enter-active {
  transition: opacity 500ms;
}

.fade2-enter-active {
  transition-delay: 60ms;
}

.fade3-enter-active {
  transition-delay: 110ms;
}

.fade1-enter, .fade2-enter, .fade3-enter {
  opacity: 0;
  transition: opacity 0s;
}
</style>
