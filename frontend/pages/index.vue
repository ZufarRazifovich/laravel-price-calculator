<script setup>
import { ref, computed } from "vue";

const products = [
  { name: "Наушники", price: 100 },
  { name: "Чехол для телефона", price: 20 }
];

const countries = [
  { code: "DE", name: "Германия", tax: 0.19 },
  { code: "IT", name: "Италия", tax: 0.22 },
  { code: "GR", name: "Греция", tax: 0.24 }
];

const selectedProduct = ref(products[0]);
const selectedCountry = ref(countries[0]);

const taxNumber = computed(() => `${selectedCountry.value.code}123456789`);

const { data, pending, execute } = useFetch("/api/calculate", {
  method: "POST",
  immediate: false,
  body: computed(() => ({
    price: selectedProduct.value.price,
    country: selectedCountry.value.code,
    taxNumber: taxNumber.value
  }))
});

const calculatePrice = async () => {
  await execute();
};
</script>

<template>
  <div class="flex items-center justify-center h-screen bg-gray-100">
    <div class="bg-white p-6 rounded-lg shadow-md w-96">
      <h1 class="text-xl font-bold text-center mb-4">Калькулятор стоимости продукта</h1>

      <form @submit.prevent="calculatePrice" class="space-y-4">
        <label class="block">
          <span class="text-gray-700">Выберите продукт:</span>
          <select v-model="selectedProduct" class="block w-full mt-1 p-2 border rounded">
            <option v-for="product in products" :key="product.name" :value="product">
              {{ product.name }} - {{ product.price }}€
            </option>
          </select>
        </label>

        <label class="block">
          <span class="text-gray-700">Выберите страну:</span>
          <select v-model="selectedCountry" class="block w-full mt-1 p-2 border rounded">
            <option v-for="country in countries" :key="country.code" :value="country">
              {{ country.name }}
            </option>
          </select>
        </label>

        <p class="text-gray-600 text-sm">Ваш налоговый номер: <strong>{{ taxNumber }}</strong></p>

        <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-600" :disabled="pending">
          {{ pending ? "Расчет..." : "Рассчитать" }}
        </button>
      </form>

      <p v-if="data" class="mt-4 text-center text-lg font-semibold">
        Итоговая стоимость: {{ data.price }}€
      </p>
    </div>
  </div>
</template>
