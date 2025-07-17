<template>
  <TransitionRoot as="template" :show="open">
    <Dialog class="relative z-10" @close="$emit('update:open', false)">
      <!-- Overlay -->
      <TransitionChild
        as="template"
        enter="ease-out duration-300"
        enter-from="opacity-0"
        enter-to="opacity-100"
        leave="ease-in duration-200"
        leave-from="opacity-100"
        leave-to="opacity-0"
      >
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
      </TransitionChild>

      <!-- Modal -->
      <div class="fixed inset-0 z-10 flex items-center justify-center">
        <TransitionChild
          as="template"
          enter="ease-out duration-300"
          enter-from="opacity-0 scale-95"
          enter-to="opacity-100 scale-100"
          leave="ease-in duration-200"
          leave-from="opacity-100 scale-100"
          leave-to="opacity-0 scale-95"
        >
          <DialogPanel class="bg-white text-black border-4 border-blue-600 rounded-[20px] shadow-xl w-5/6 lg:w-3/6 p-6">
            <div class="flex flex-col items-start justify-start w-full h-full px-2 pt-4">
              <div class="flex justify-center w-full h-full py-4 space-x-8 items-start">
                <!-- Left Image -->
                <div class="w-1/2">
                  <img
                    :src="selectedProduct.image ? `/${selectedProduct.image}` : '/images/placeholder.jpg'"
                    alt="Product Image"
                    class="object-cover h-full rounded-2xl"
                  />
                </div>

                <!-- Right Details -->
                <div class="flex flex-col justify-between w-1/2 h-full">
                  <p class="text-3xl font-bold text-black break-words">
                    {{ selectedProduct.name }}
                    <span
                      v-if="selectedProduct.discount && selectedProduct.discount > 0"
                      class="ml-2 inline-block px-2 py-1 text-sm font-medium text-white bg-red-600 rounded"
                    >
                      {{ selectedProduct.discount }}% OFF
                    </span>
                  </p>

                  <!-- Details Grid -->
                  <div class="grid grid-cols-2 gap-x-8 gap-y-4 mt-6 text-base">
                    <p class="text-gray-600 italic text-lg">
                      {{ selectedProduct.category?.name ?? "No Category" }}
                    </p>
                    <p class="text-black font-medium text-lg">
                      <span class="text-gray-600 font-normal">Supplier:</span>
                      {{ selectedProduct.supplier?.name || "N/A" }}
                    </p>
                    <p class="text-black font-medium text-lg">
                      <span class="text-gray-600 font-normal">Product Code:</span>
                      {{ selectedProduct?.code ?? "N/A" }}
                    </p>
                    <p class="text-black font-medium text-lg">
                      <span class="text-gray-600 font-normal">Batch No:</span>
                      {{ selectedProduct?.batch_no ?? "N/A" }}
                    </p>
                    <p class="text-gray-600 text-lg">
                      Color:
                      <span class="font-semibold text-black">{{ selectedProduct?.color?.name ?? "N/A" }}</span>
                    </p>
                    <p class="text-gray-600 text-lg">
                      Size:
                      <span class="px-3 py-1 font-semibold text-black border border-gray-500 rounded-xl">
                        {{ selectedProduct?.size?.name ?? "N/A" }}
                      </span>
                    </p>
                  </div>

                  <!-- Prices & Quantity -->
                  <div class="grid grid-cols-2 gap-x-10 gap-y-6 mt-6 text-lg">
                    <div>
                      <p class="text-gray-600">Selling Price:</p>
                      <p class="font-bold text-black">{{ selectedProduct?.selling_price ?? "N/A" }} LKR</p>
                    </div>
                    <div>
                      <p class="text-gray-600">Cost Price:</p>
                      <p class="font-bold text-black">{{ selectedProduct?.cost_price ?? "N/A" }} LKR</p>
                    </div>
                    <div>
                      <p class="text-gray-600">Discount Price:</p>
                      <p class="font-bold text-black">
                        {{
                          selectedProduct.selling_price
                            ? (
                                selectedProduct.selling_price - (selectedProduct.selling_price * selectedProduct.discount) / 100
                              ).toFixed(2)
                            : "N/A"
                        }} LKR
                      </p>
                    </div>
                    <div>
                      <p class="text-gray-600">Quantity:</p>
                      <p class="font-bold text-black">{{ selectedProduct?.stock_quantity ?? "N/A" }}</p>
                      <p class="mt-4 text-gray-600">Preorder Level:</p>
                      <p class="font-bold text-black">{{ selectedProduct?.preorder_level_qty ?? "N/A" }}</p>
                    </div>
                    <div class="col-span-2 flex justify-between items-start gap-4 mt-4">
                      <div>
                        <p class="text-gray-600">Created On:</p>
                        <p class="font-bold text-black">{{ formattedDate }}</p>
                      </div>
                      <div class="text-right">
                        <p class="text-gray-600">Certificate:</p>
                        <a
                          v-if="selectedProduct?.certificate_path"
                          :href="`${selectedProduct.certificate_path}`"
                          target="_blank"
                          class="mt-1 inline-block px-4 py-2 text-white bg-green-600 rounded hover:bg-green-700 transition"
                        >
                          View Certificate
                        </a>
                        <span v-else class="ml-2 text-gray-500 italic">Not Uploaded</span>
                      </div>
                    </div>
                  </div>

                  <!-- Barcode Count & Button -->
                  <div class="mt-6 w-full">
                    <div class="w-full mb-4">
                      <label for="barcodeCount" class="block text-gray-700 font-medium mb-1">Number of Barcodes</label>
                      <input
                        type="number"
                        id="barcodeCount"
                        v-model="barcodeCount"
                        min="1"
                        class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring focus:ring-blue-400"
                      />
                    </div>

                    <button
                      v-if="HasRole(['Admin', 'Manager'])"
                      @click="generateAndPrintBarcode"
                      class="w-full px-4 py-3 text-xl font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700"
                    >
                      Print Bar Code
                    </button>
                  </div>
                </div>
              </div>

              <!-- Hidden Print Area -->
              <div id="printContainer" class="print-container hidden"></div>
            </div>
          </DialogPanel>
        </TransitionChild>
      </div>
    </Dialog>
  </TransitionRoot>
</template>

<script setup>
import {
  Dialog,
  DialogPanel,
  TransitionChild,
  TransitionRoot,
} from "@headlessui/vue";
import { ref, computed } from "vue";
import dayjs from "dayjs";
import advancedFormat from "dayjs/plugin/advancedFormat";
import { HasRole } from "@/Utils/Permissions";

dayjs.extend(advancedFormat);
const emit = defineEmits(["update:open"]);

const { selectedProduct } = defineProps({
  open: Boolean,
  categories: Array,
  colors: Array,
  sizes: Array,
  selectedProduct: Object,
});

const barcodeCount = ref(1);

const formattedDate = computed(() =>
  selectedProduct?.created_at
    ? dayjs(selectedProduct.created_at).format("Do MMMM YYYY")
    : ""
);

function generateAndPrintBarcode() {
  const value = selectedProduct?.code?.trim();
  const count = parseInt(barcodeCount.value);

  if (!value || isNaN(count) || count <= 0) {
    alert("Invalid product code or barcode count.");
    return;
  }

  const printWindow = window.open("", "_blank");

  if (!printWindow) {
    alert("Pop-up blocked. Please allow pop-ups for this site to generate barcodes.");
    return;
  }

  const htmlContent = `
<!DOCTYPE html>
<html>
  <head>
    <title>Print Barcodes</title>
    <meta charset="UTF-8">
    <style>
      * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
      }

      body {
        font-family: Arial, sans-serif;
        background: white;
        line-height: 1;
      }

      .barcode-container {
        width: 75mm;
        margin: 0 auto;
        padding: 0;
        display: flex;
        flex-direction: column;
        gap: 0mm;
      }

      .barcode-row {
        display: flex;
        justify-content: space-between;
        gap: 0mm;
      }

      .barcode-label {
        width: 37.5mm;
        height: 25mm;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 0.5mm;
        text-align: center;
        background: white;
        box-sizing: border-box;
        overflow: hidden;
      }

      .product-name {
        font-size: 5px;
        font-weight: bold;
        line-height: 1;
        margin-bottom: 0.5mm;
        max-height: 4mm;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        width: 100%;
      }

      .barcode-svg {
        flex: 1;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 12mm;
        max-height: 15mm;
        width: 100%;
      }

      .barcode-svg svg {
        max-width: 100%;
        max-height: 100%;
        width: auto;
        height: auto;
      }

      .bottom-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 5px;
        font-family: monospace;
        width: 100%;
        margin-top: 0.5mm;
        white-space: nowrap;
        line-height: 1;
          padding : 0 7mm;
      }

      .bottom-info span {
        max-width: 48%;
        overflow: hidden;
        text-overflow: ellipsis;
      }

      @media print {
        @page {
          margin: 0;
          size: 75mm auto;
        }

        body {
          margin: 0;
          padding: 0;
          -webkit-print-color-adjust: exact;
          print-color-adjust: exact;
        }

        .barcode-label {
          border: none;
          break-inside: avoid;
        }

        .barcode-row {
          break-inside: avoid;
        }
      }
    </style>
  </head>
  <body>
    <div class="barcode-container">
      ${(() => {
        const rows = [];
        const labels = [];

        // Create array of barcode labels
        for (let i = 0; i < count; i++) {
          labels.push({
            id: i,
            name: selectedProduct.name || '',
            code: value,
            price: selectedProduct.selling_price || 0
          });
        }

        // Group labels into rows of 2
        for (let i = 0; i < labels.length; i += 2) {
          const first = labels[i];
          const second = labels[i + 1];
          rows.push(`
            <div class="barcode-row">
              ${[first, second].filter(Boolean).map(label => `
                <div class="barcode-label">
                  <div class="product-name">${escapeHtml(label.name)}</div>
                  <div class="barcode-svg">
                    <svg id="barcode-${label.id}"></svg>
                  </div>
                  <div class="bottom-info">
                    <span>${escapeHtml(label.code)}</span>
                    <span>Rs:${parseFloat(label.price).toFixed(2)}</span>
                  </div>
                </div>
              `).join('')}
            </div>
          `);
        }
        return rows.join('');
      })()}
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"><\/script>
    <script>
      function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
      }

      window.onload = function () {
        try {
          ${(() => {
            const scripts = [];
            for (let i = 0; i < count; i++) {
              scripts.push(`
                try {
                  JsBarcode("#barcode-${i}", "${value}", {
                    format: "CODE128",
                    lineColor: "#000",
                    width: 1,
                    height: 40,
                    displayValue: false,
                    margin: 0,
                    background: "transparent"
                  });
                } catch (e) {
                  console.error("Barcode error for ${i}:", e);
                  document.getElementById("barcode-${i}").innerHTML = '<text x="50%" y="50%" text-anchor="middle" font-size="8">Invalid</text>';
                }
              `);
            }
            return scripts.join('');
          })()}

          setTimeout(() => {
            window.print();
          }, 500);
        } catch (e) {
          console.error("Printing error:", e);
          alert("Error generating barcodes. Please try again.");
        }
      };

      window.onafterprint = function () {
        setTimeout(() => {
          window.close();
        }, 100);
      };
    <\/script>
  </body>
</html>
`;

  printWindow.document.open();
  printWindow.document.write(htmlContent);
  printWindow.document.close();
}

// Helper function for HTML escaping
const escapeHtml = (text) => {
  const div = document.createElement('div');
  div.textContent = text;
  return div.innerHTML;
};
</script>

<style>
@media print {
  #printContainer {
    display: flex;
    flex-wrap: wrap;
    justify-content: start;
    align-items: flex-start;
    width: 100%;
    padding: 10mm;
    gap: 10mm;
  }

  .print-content {
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 50mm;
    padding: 5mm;
    box-sizing: border-box;
  }

  .product-details,
  .product-code {
    font-size: 10px;
    font-weight: bold;
    color: #000;
    margin-top: 4px;
    text-align: center;
  }
}
</style>
