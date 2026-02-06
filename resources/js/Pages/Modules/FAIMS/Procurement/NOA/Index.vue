<template>
  <PageHeader title="Notice of Awards" pageTitle="List" />
  <b-row>
    <h5>
      <div>
        <span class="font-weight-bold">
          BAC Resolution No:
          <u class="text-info">
            <span class="bg-light p-1">
              {{ bac_resolution.code }}
            </span>
          </u>
        </span>
      </div>
    </h5>
  </b-row>

  <b-row class="g-2 mb-3 mt-n2">
    <b-col lg>
      <div class="input-group mb-1">
        <b-button type="button" variant="info" @click="goBackPage()">
          <i class="ri-arrow-left-line align-bottom me-1"></i> Back
        </b-button>
        <span class="input-group-text"> <i class="ri-search-line search-icon"></i></span>
        <input
          type="text"
          v-model="filter.keyword"
          placeholder="Search Notice of Awards"
          class="form-control"
          style="width: 60%"
        />
        <span
          @click="refresh()"
          class="input-group-text"
          v-b-tooltip.hover
          title="Refresh"
          style="cursor: pointer"
        >
          <i class="bx bx-refresh search-icon"></i>
        </span>
        <b-button
          v-if="procurement.status_id == 44"
          type="button"
          variant="primary"
          @click="openNOA()"
        >
          <i class="ri-add-circle-fill align-bottom me-1"></i> New
        </b-button>
      </div>
    </b-col>
  </b-row>

  <b-card no-body>
      <b-tabs card  >
        <b-tab title="All" active >
            <table class="table mb-0">
        <thead class="table-light">
          <tr class="fs-11">
            <th>#</th>
            <th>NOA No.</th>
            <th>Date Created</th>
            <th>Status</th>
            <th class="text-center">Actions</th>
          </tr>
        </thead>

        <tbody>
          <tr class="custom-hover-row" v-for="(list, index) in lists" v-bind:key="index" @click="selectRow(list.id)" :class="{ 'bg-info-subtle': selectedRow === list.id }">
            <td>{{ index + 1 }}</td>
            <td>{{ list.code }}</td>
            <td>{{ list.created_at }}</td>
            <td>
              <b-badge :class="list.status.bg">{{ list.status?.name }}</b-badge>
            </td>
            <td class="text-center">
              <div class="d-flex gap-3 justify-content-center">
                <div class="dropdown" @click.stop>
                  <button
                    class="btn btn-light btn-icon btn-sm dropdown material-shadow-none"
                    type="button"
                    data-bs-toggle="dropdown"
                    aria-expanded="false"
                  >
                    <i class="ri-more-fill align-bottom"></i>
                  </button>
                  <ul class="dropdown-menu dropdownmenu-primary dropdown-menu-end">
                    <li
                      @click="viewComments(list)"
                      class="dropdown-item d-flex align-items-center"
                      role="button"
                    >
                      <i class="ri-eye-fill align-bottom me-1"></i>View
                    </li>
                    <li
                      @click="printNOA(list)"
                      class="dropdown-item d-flex align-items-center"
                      role="button"
                    >
                      <i class="ri-printer-fill align-bottom me-1"></i>Print
                    </li>
                    <li
                      v-if="list.status.name == 'Pending' || list.status.name == 'Served to Supplier'"
                      @click="updateStatus(list)"
                      class="dropdown-item d-flex align-items-center"
                      role="button"
                    >
                      <i class="ri-edit-2-fill align-bottom me-1"></i>Update Status
                    </li>
                    <li
                      v-if=" list.status.name == 'Served to Supplier'"
                      @click="notConformed(list)"
                      class="dropdown-item d-flex align-items-center"
                      role="button"
                    >
                      <i class="ri-edit-2-fill align-bottom me-1"></i>Not Conformed
                    </li>
   
                    <li
                      v-if="list.status.name == 'Conformed' || list.status.name == 'PO Issued' ||
                            list.status.name == 'PO Pending' ||  list.status.name == 'PO Conformed' ||
                            list.status.name == 'Delivered/For Inspection' 	"
                      @click="goPOPage(list)"
                      class="dropdown-item d-flex align-items-center"
                      role="button"
                    >
                      <i class="ri-eye-2-fill align-bottom me-1"></i> Purchase Order
                    </li>
                  </ul>
                </div>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
      <Pagination
        class="ms-2 me-2"
        v-if="meta"
        @fetch="fetch"
        :lists="lists.length"
        :links="links"
        :pagination="meta"
      />
                
        </b-tab>
        <b-tab title="Comments" v-if="selectedRow">
          <b-card-text>Comments</b-card-text>
        </b-tab>
        <b-tab title="Logs" v-if="selectedRow">
          <b-card-text>Status Logs</b-card-text>
        </b-tab>
      </b-tabs>
  </b-card>


  <NOA :procurement="procurement" ref="NOA" />
  <UpdateStatus :procurement="procurement" @add="fetch()" ref="updateStatus" />
</template>
<script>
import _ from "lodash";

import PageHeader from "@/Shared/Components/PageHeader.vue";
import Pagination from "@/Shared/Components/Pagination.vue";
import NOA from "../Modals/NOA.vue";
import UpdateStatus from "../Modals/UpdateStatus.vue";
import { router } from "@inertiajs/vue3";
export default {
  props: ["bac_resolution", "procurement" ],
  components: { PageHeader, Pagination, NOA, UpdateStatus },
  data() {
    return {
      currentUrl: window.location.origin,
      lists: [],
      meta: {},
      links: {},
      filter: {
        keyword: null,
      },
      selectedRow: null,
      index: null,
    };
  },
  watch: {
    "filter.keyword"(newVal) {
      this.checkSearchStr(newVal);
    },
  },
  created() {
    this.fetch();
  },
  methods: {
    checkSearchStr: _.debounce(function (string) {
      this.fetch();
    }, 300),
    fetch(page_url) {
      page_url = "/faims/notice-of-awards";
      axios
        .get(page_url, {
          params: {
            keyword: this.filter.keyword,
            option: "lists",
            bac_resolution_id: this.bac_resolution.id,
          },
        })
        .then((response) => {
          if (response) {
            this.lists = response.data.data;
            this.meta = response.data.meta;
            this.links = response.data.links;
          }
        })
        .catch((err) => console.log(err));
    },

    openNOA() {
      this.$refs.NOA.show(type);
    },

    viewNOA(data) {
      this.$refs.NOA.show(data);
    },

    updateStatus(data) {
      let type = "NOA";
      this.$refs.updateStatus.show(data, type);
    },

    notConformed(data) {
      let type = "NOA Not Conformed";
      this.$refs.updateStatus.show(data, type);
    },

    printNOA(data) {
      window.open(
        "/faims/notice-of-awards/" + data.id + "?option=print&type=notice_of_awards"
      );
    },

    viewComments(data) {
      this.$refs.updateStatus.show(data, type);
    },

    goBackPage() {
      router.get("/faims/procurements/" + this.procurement.id, {
        option: "bac_resolutions",
      });
    },

    goPOPage(data) {
      router.get(
        "/faims/procurements/" +
          this.procurement.id +
          "?noa_id=" +
          data.id +
          "&option=purchase_order"
      );
    },

    selectRow(selected_id) {
        this.selectedRow = selected_id;
    },
  },
};
</script>

<style scoped>
.custom-hover-row:hover {
  background-color: hsl(0, 29%, 97%);
}
</style>
