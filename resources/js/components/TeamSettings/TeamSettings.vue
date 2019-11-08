<template>
  <div class="card mb-1 team">
    <section id="hydra-sec">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-7">
            <div class="row">
              <div class="parent-box">
                <!--============================================ TEAM LEADER START ===========================================-->
                <div class="flex-container">
                  <h6>Team Leader</h6>
                  <div class="panel-box">
                    <div class="leader-panel">{{name}}</div>
                  </div>
                </div>
                <!--============================================ TEAM LEADER END ===========================================-->
                <!--============================================ SENIOR START ===========================================-->
                <div class="flex-container">
                  <h6>Senior</h6>
                  <draggable
                    :list="seniors"
                    group="people"
                    class="panel-box"
                    @change="onSeniorChanged"
                    :move="onSeniorMove"
                    id="seniors"
                  >
                    <div
                      class="leader-panel"
                      :class="{'active' : selectedSenior.id === senior.id}"
                      v-for="(senior, index) in seniors"
                      @click="onSeniorSelected(senior, index)"
                      :key="index"
                    >{{senior.first_name + ' ' + senior.last_name}}</div>
                  </draggable>
                </div>
                <!--============================================ SENIOR END ===========================================-->
                <!--============================================ JUNIOR START ===========================================-->
                <div class="flex-container">
                  <h6>Junior</h6>
                  <draggable
                    v-if="seniors.find(e => selectedSenior.id && e.id == selectedSenior.id) !== undefined"
                    class="panel-box"
                    :list="seniors.find(e => e.id == selectedSenior.id).juniors"
                    group="people"
                    @change="onJuniorChanged"
                    id="juniors"
                  >
                    <div
                      v-for="(junior, index) in seniors.find(e => e.id == selectedSenior.id).juniors"
                      :key="index"
                      class="leader-panel"
                    >{{junior.first_name + ' ' + junior.last_name}}</div>
                  </draggable>
                  <div v-else class="panel-box">No Member found.</div>
                </div>
                <!--============================================ JUNIOR END ===========================================-->
              </div>
            </div>
          </div>
          <!--============================================ ALL USERS START ===========================================-->
          <div class="col-lg-3">
            <div class="right-panel">
              <div class="all-members-panel">
                <i class="fas fa-users"></i> All Members
              </div>
              <draggable
                class="draggable-div-parent"
                :list="allUsers"
                group="people"
                id="allusers"
                @change="onAllUsersChanged"
              >
                <div
                  v-for="(user, index) in allUsers"
                  :key="index"
                  class="leader-panel"
                >{{user.first_name + ' ' + user.last_name}}</div>
              </draggable>
            </div>
          </div>
          <!--============================================ ALL USERS END ===========================================-->
        </div>
      </div>
      <pre v-if="false" > {{JSON.stringify(seniors, null, 2)}}</pre>
    </section>
  </div>
</template>

<script>
import draggable from 'vuedraggable';
import axios from 'axios';
import api from '../../api';
export default {
  name: 'TeamSettings',
  components: {
    draggable,
  },
  props: {
    name: {
      type: String,
      default: ''
    }
  },
  data() {
    return {
      seniors: [],
      allUsers: [],
      selectedSenior: { id: 0 },
    };
  },
  methods: {
    getAllUsers() {
      api
        .getAllFreeUsers()
        .then(res => {
          if (res.data !== undefined) this.allUsers = res.data;
        })
        .catch(err => console.log(err));
    },
    getTeam() {
      api
        .getTeam()
        .then(res => {
          if (res.data !== undefined) this.seniors = res.data;
        })
        .catch(err => console.log(err));
    },
    onChangeTeamUserStatus({ mode, id, senior_id }) {
      /**
       * params:
       *
       * mode: 1 => user will get free
       * mode: 2 => user will be promoted to senior
       * mode: 3 => user will be promoted/demoted junior
       *
       * senior_id: if user is junior, give his senior id
       * id: give user's id
       *
       *
       */
      let obj = {
        mode,
        id,
        senior_id,
      };
      api
        .changeUserStatus(obj)
        .then(res => {
        })
        .catch(err => console.log(err));
    },
    makeEmptyJuniorArray(index) {
      if (this.seniors[index].juniors === undefined)
        this.$set(this.seniors[index], 'juniors', []);
    },
    onSeniorSelected(senior, index) {
      this.makeEmptyJuniorArray(index);
      this.selectedSenior = senior;
    },
    onSeniorChanged(env) {
      if (env.added && env.added.element) {
        this.makeEmptyJuniorArray(env.added.newIndex);
        this.selectedSenior = this.seniors[env.added.newIndex];
        if (!env.added.element.juniors) env.added.element.juniors = [];
        let obj = {
          mode: 2,
          id: env.added.element.id,
          senior_id: null,
        };
        this.onChangeTeamUserStatus(obj);
      }
    },
    onJuniorChanged(env) {
      if (env.added && env.added.element) {
        if (env.added.element.juniors) {
          //if user was senior before then, make sure to empty his juniors
          this.allUsers = [...this.allUsers, ...env.added.element.juniors];
          env.added.element.juniors = [];
        }
        let senior_id = this.selectedSenior.id;
        let obj = {
          mode: 3,
          id: env.added.element.id,
          senior_id,
        };
        this.onChangeTeamUserStatus(obj);
      }
    },
    onAllUsersChanged(env) {
      if (env.added && env.added.element) {
        if (env.added.element.juniors) {
          //if user was senior before then, make sure to empty his juniors
          this.allUsers = [...this.allUsers, ...env.added.element.juniors];
          env.added.element.juniors = [];
        }
        this.onChangeTeamUserStatus({
          mode: 1,
          id: env.added.element.id,
          senior_id: null,
        });
      }
    },
    onSeniorMove(env, originalEnv) {
      //if element is dragged inside its own parent, then prevent it
      return !(
        env.draggedContext.element.id === this.selectedSenior.id &&
        env.to.id === 'juniors'
      );
    },
  },
  mounted() {
    //make get api call
    this.getAllUsers();
    this.getTeam();
  },
};
</script>


<style lang="scss" scoped>
.panel-box {
  background: #f6f6f6;
  :-webkit-scrollbar {
    width: 5px;
  }

  .leader-panel:first-child {
    margin-top: 15px;
  }
}

.flex-container {
  display: flex;
  width: 200px;
  flex-wrap: wrap;
  flex: 1;
  flex-grow: 3;
  width: 100%;
  align-content: space-between;
  > div .team-box {
    background-color: #6e7379;
    width: 100px;
    margin: 10px;
    text-align: center;
    line-height: 75px;
    font-size: 16px;
    color: #fff;
    border-radius: 5px;
    position: relative;
  }
  .panel-box {
    background: #f6f6f6;
    height: calc(100vh - 210px);
    width: 100%;
    margin-right: 25px;
    overflow-y: auto;
  }
}

.parent-box {
  display: flex;
  display: -webkit-flex;
  justify-content: space-between;
  width: 100%;
}

.leader-panel {
  color: #42474b;
  padding-top: 10px;
  padding-bottom: 10px;
  padding-left: 15px;
  position: relative;
  margin: 10px;
  :hover {
    background: #d4d5d7;
  }
  &.active {
    background: #007bff;
    color: white;
    border-radius: 16px;
  }
}

.all-members-panel {
  background: #495058;
  padding-top: 10px;
  padding-bottom: 10px;
  padding-left: 15px;
  color: #fff;
}

.right-panel {
  overflow-y: auto;
  .draggable-div-parent {
    height: calc(100vh - 210px);
  }
}

#hydra-sec {
  margin-top: 40px;
}

.team-box .fas.fa-long-arrow-alt-right {
  color: #6e7379;
  position: absolute;
  top: 50%;
  right: -50px;
  transform: translate(-50%, -50%);
  font-size: 40px;
}
</style>

