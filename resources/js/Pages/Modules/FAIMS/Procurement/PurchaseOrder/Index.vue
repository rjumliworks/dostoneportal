<template>
  <!-- Page Header -->
  <PageHeader title="Purchase Order" pageTitle="List" />

  <b-row class="g-2 mb-3 mt-n2">
    <b-col lg>
      <div class="input-group mb-1">
        <b-button type="button" variant="info" @click="goBackPage()" class="me-1">
          <i class="ri-arrow-left-line align-bottom me-1"></i> Back
        </b-button>

        <b-dropdown text="Actions" type="button" variant="primary">
          <b-dropdown-item @click="createPO()" v-if="!purchase_order">
            <i class="ri-add-fill align-bottom me-1"></i>
            Create
          </b-dropdown-item>
          <b-dropdown-item v-if="purchase_order">
            <i class="ri-pencil-fill align-bottom me-1"></i>
            Edit
          </b-dropdown-item>

          <b-dropdown-item v-if="purchase_order && purchase_order.status.name != 'Delivered/For Inspection'" @click="updateStatus(purchase_order)">
            <i class="ri-edit-fill align-bottom me-1"></i>
            Update Status
          </b-dropdown-item>

            <b-dropdown-item v-if="purchase_order && purchase_order.status.name == 'Served to Supplier'" @click="notConformed(purchase_order)">
            <i class="ri-edit-fill align-bottom me-1"></i>
            Not Conformed
          </b-dropdown-item>

          <b-dropdown-item v-if="purchase_order && purchase_order.status.name == 'Conformed'" @click="printNTP(purchase_order)">
            <i class="ri-file-fill align-bottom me-1"></i>
            Notice to Proceed
          </b-dropdown-item>
          <b-dropdown-item v-if="purchase_order" @click="printPO(purchase_order)">
            <i class="ri-printer-fill align-bottom me-1"></i>
            Print
          </b-dropdown-item>
        </b-dropdown>
      </div>
    </b-col>
  </b-row>

  <!-- Main Content -->
  <div class="chat-wrapper d-lg-flex gap-1 mx-n4 mt-n4 p-1" style="height: 450px">
    <b-card class="w-100">
      <!-- Purchase Order Content -->
      <div
        v-if="purchase_order"
        class="file-manager-content w-100 p-4 pb-0"
        style="height: calc(90vh - 180px); overflow: auto"
        ref="box"
      >
        <b-row class="mb-3">
        
          <b-col>
            <div class="d-flex flex-column">
              <span class="font-weight-bold">
              NOA No:
              <u class="text-info">
                <span class="bg-light p-1">{{ noa.code }}</span>
              </u>
            </span>
              <span
                >Supplier: <b>{{ noa.procurement_quotation.supplier.name }}</b></span
              >
              <span
                >Address:
                <b>{{ noa.procurement_quotation.supplier.address?.address }}</b>
              </span>
              <span
                >TIN:
                <b>{{ noa.procurement_quotation.supplier.tin }}</b>
              </span>
            </div>
          </b-col>

          <b-col>
            <div class="d-flex flex-column">
              <span
                >PO No.:
                <b>
                  <u class="text-info">
                    <span class="bg-light p-1">{{ purchase_order.code }}</span>
                  </u>
                </b>
              </span>
              <span
                >PO Date:
                <b>{{ purchase_order.po_date }}</b>
              </span>
              <span
                >Mode of Procurement:
                <b v-for="code in procurement.codes">
                  {{ code.procurement_code.mode_of_procurement.name }}
                </b>
              </span>
                  <span
                >Status:
                <b-badge :class="purchase_order.status.bg"> {{ purchase_order.status?.name }}</b-badge>
              </span>
            </div>
          </b-col>
        </b-row>

        <!-- Items Table -->
        <table class="table mb-0">
          <thead class="table-light">
            <tr class="fs-11">
              <th>Item No.</th>
              <th>Unit</th>
              <th>Description</th>
              <th>Quantity</th>
              <th>Unit Cost</th>
              <th>Amount</th>
            </tr>
          </thead>
          <tbody style="vertical-align: top">
            <tr v-for="(item, index) in noa.items" :key="index">
              <td>{{ item.item.item.item_no }}</td>
              <td>
                {{
                  item.item.item.item_quantity > 1
                    ? item.item.item.item_unit_type.name_long
                    : item.item.item.item_unit_type.name_short
                }}
              </td>

              <td>
                <div v-html="item.item.item.item_description"></div>
              </td>
              <td>{{ item.item.item.item_quantity }}</td>

              <td>{{ formatCurrency(item.item.bid_price) }}</td>

              <td>
                {{ formatCurrency(item.item.bid_price * item.item.item.item_quantity) }}
              </td>
            </tr>

            <!-- Total Row -->
            <tr class="fw-bold">
              <td colspan="5" class="text-end">Total:</td>
              <td>{{ formatCurrency(totalAmount) }}</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Empty State -->
      <div
        v-else
        class="w-100 h-100 d-flex justify-content-center align-items-center"
        style="height: calc(90vh - 180px)"
      >
        <span
          class="w-100 h-100 d-flex justify-content-center align-items-center text-center border-0"
        >
          <h5 class="text-muted">
            Please create Purchase Order (PO) first to view details.
          </h5>
        </span>
      </div>
    </b-card>
  </div>

  <!-- Update Status Modal -->
  <PurchaseOrder
    :procurement="procurement"
    :noa="noa"
    :dropdowns="dropdowns"
    @add="fetch()"
    ref="create"
  />
  <UpdateStatus :procurement="procurement" @add="fetch()" ref="updateStatus" />
</template>

<script>
import _ from "lodash";
import PageHeader from "@/Shared/Components/PageHeader.vue";
import Pagination from "@/Shared/Components/Pagination.vue";
import UpdateStatus from "../Modals/UpdateStatus.vue";
import { router } from "@inertiajs/vue3";
import PurchaseOrder from "../Modals/PurchaseOrder.vue";

export default {
  props: ["noa", "procurement", "dropdowns"],
  components: { PageHeader, Pagination, UpdateStatus, PurchaseOrder },
  data() {
    return {
      currentUrl: window.location.origin,
      meta: {},
      links: {},
      index: null,
      purchase_order: null,
    };
  },
  created() {
    this.fetch();
  },

  computed: {
    totalAmount() {
      return this.noa.items.reduce((sum, item) => {
        return sum + item.item.bid_price * item.item.item.item_quantity;
      }, 0);
    },
  },

  methods: {
    checkSearchStr: _.debounce(function (string) {
      this.fetch();
    }, 300),
    fetch(page_url) {
      page_url = "/faims/purchase-orders";
      axios
        .get(page_url, {
          params: {
            option: "purchase_order",
            noa_id: this.noa.id,
          },
        })
        .then((response) => {
          if (response) {
            this.purchase_order = response.data;
          }
        })
        .catch((err) => console.log(err));
    },

    formatCurrency(value) {
      return new Intl.NumberFormat("en-PH", {
        style: "currency",
        currency: "PHP",
      }).format(value);
    },

    createPO() {
      this.$refs.create.show();
    },
    updateStatus(data) {
      this.$refs.updateStatus.show(data, "PO");
    },
    notConformed(data) {
      this.$refs.updateStatus.show(data, "PO Not Conformed");
    },
    printPO(data) {
      window.open(`/faims/purchase-orders/${data.id}?option=print&type=purchase_order`);
    },
    printNTP(data) {
      window.open(`/faims/purchase-orders/${data.id}?option=print&type=notice_to_proceed`);
    },
    goBackPage() {
      router.get(`/faims/procurements/${this.procurement.id}`, {
        option: "bac_resolutions",
      });
    },
  },
};
</script>

<style scoped>
.custom-hover-row:hover {
  background-color: hsl(0, 29%, 97%);
}
</style>
