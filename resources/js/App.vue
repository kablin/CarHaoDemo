<script setup>
import axios from "axios"
import { ref } from 'vue';
import { watchEffect } from 'vue';
import { watch } from 'vue';
import { useRouter } from 'vue-router'
import { useRoute } from 'vue-router'
import { Modal } from 'flowbite';
import { onMounted } from 'vue'
import { computed } from "vue";
import 'vue3-carousel/dist/carousel.css'

import { Carousel, Slide, Pagination, Navigation } from 'vue3-carousel'


import Slider from 'primevue/slider';


const config = {
    itemsToShow: 1
}



const response = ref();
const router = useRouter()
const route = useRoute()
const rowPerPage = ref(10);
const page = ref(1);

const brands = ref({})
const models = ref({})
const models_filtered = ref({})
const limits = ref({})
const engines = ref({})
const drives = ref({})

const image_path = ref('')


const filter_model = ref(0)
const filter_brand = ref(0)
const filter_engine = ref(0)
const filter_drive = ref(0)

const filter_price = ref([])
const filter_price_min = ref(0)
const filter_price_max = ref(0)


const filter_year = ref([])
const filter_year_min = ref(0)
const filter_year_max = ref(0)



const url_init = ref(false)
const guides_init = ref(false)


let modal



const query = computed(() => route.query)

watch(query, newquery => {
    if (!url_init.value) {

        if (page.value != newquery.currentPage ) page.value = newquery.currentPage
        if (rowPerPage.value != newquery.perPage && newquery.perPage != undefined) rowPerPage.value = newquery.perPage
        if (filter_model.value != newquery.filter_model) filter_model.value = newquery.filter_model
        if (filter_brand.value != newquery.filter_brand) filter_brand.value = newquery.filter_brand
        if (filter_engine.value != newquery.filter_engine) filter_engine.value = newquery.filter_engine
        if (filter_drive.value != newquery.filter_drive) filter_drive.value = newquery.filter_drive

        if (filter_price_min.value != newquery.filter_price_min) filter_price_min.value = parseInt(newquery.filter_price_min)
        if (filter_price_max.value != newquery.filter_price_max) filter_price_max.value = parseInt(newquery.filter_price_max)
        if (filter_year_min.value != newquery.filter_year_min) filter_year_min.value = parseInt(newquery.filter_year_min)
        if (filter_year_max.value != newquery.filter_year_max) filter_year_max.value = parseInt(newquery.filter_year_max)
        url_init.value = true

        getGuides()
    }
})


onMounted(() => {

    modal = new Modal(document.getElementById('default-modal'), {}, {});


})



const setParams = () => {
    router.replace({
        query: {
            currentPage: page.value,
            perPage: rowPerPage.value,
            filter_model: filter_model.value,
            filter_brand: filter_brand.value,
            filter_engine: filter_engine.value,
            filter_drive: filter_drive.value,
            filter_price_min: (filter_price.value[0] != limits.value['min_price']) ? filter_price.value[0] : undefined,
            filter_price_max: (filter_price.value[1] != limits.value['max_price']) ? filter_price.value[1] : undefined,

            filter_year_min: (filter_year.value[0] != limits.value['min_year']) ? filter_year.value[0] : undefined,
            filter_year_max: (filter_year.value[1] != limits.value['max_year']) ? filter_year.value[1] : undefined,


        }
    })


}



const fetchRows = async () => {
    if (url_init.value && guides_init.value)
        try {

            filter_price_min.value = filter_price.value[0]
            filter_price_max.value = filter_price.value[1]

            filter_year_min.value = filter_year.value[0]
            filter_year_max.value = filter_year.value[1]


            const params = {
                currentPage: page.value,
                perPage: rowPerPage.value,
                filter_model: filter_model.value,
                filter_brand: filter_brand.value,
                filter_engine: filter_engine.value,
                filter_drive: filter_drive.value,
                filter_price_min: filter_price_min.value,
                filter_price_max: filter_price_max.value,
                filter_year_min: filter_year_min.value,
                filter_year_max: filter_year_max.value,



            }
            response.value = await axios.get("/getCarsList", { params })

            if (response.value.data.last_page < page.value) page.value = response.value.data.last_page

            setParams()
        } catch (error) {

            console.log(error);
        }
};


const getGuides = async () => {

    try {
        const data = await axios.get("/getGuides")
        brands.value = data.data['brands']
        models.value = data.data['car_models']
        models_filtered.value = models.value
        engines.value = data.data['engine_types']
        drives.value = data.data['dive_types']
        limits.value = data.data['limits'];

        limits.value['min_year'] = new Date(limits.value['min_date']).getFullYear()
        limits.value['max_year'] = new Date(limits.value['max_date']).getFullYear()



        if (!filter_price_min.value) {
            filter_price.value.push(limits.value['min_price'])
            filter_price_min.value = limits.value['min_price']
        }
        else
            filter_price.value.push(filter_price_min.value)

        if (!filter_price_max.value) {
            filter_price_max.value = limits.value['max_price']
            filter_price.value.push(limits.value['max_price'])
        }
        else
            filter_price.value.push(filter_price_max.value)


        if (!filter_year_min.value) {
            filter_year.value.push(limits.value['min_year'])
            filter_year_min.value = limits.value['min_year']
        }
        else
            filter_year.value.push(filter_year_min.value)

        if (!filter_year_max.value) {
            filter_year_max.value = limits.value['max_year']
            filter_year.value.push(limits.value['max_year'])
        }
        else
            filter_year.value.push(filter_year_max.value)


        guides_init.value = true



    } catch (error) {

        console.log(error);
    }
};




const setPage = (newPage) => {
    page.value = newPage
}


const selectBrand = () => {
    if (filter_brand.value == 0) models_filtered.value = models.value
    else {
        models_filtered.value = models.value.filter(
            (v) => v.brand_id == filter_brand.value
        )
    }
}





watchEffect(fetchRows);

</script>





<template>
    <div class=" px-4">
        <div v-if="response" class="inline-flex w-full mb-5 justify-between">
            <ul class="inline-flex -space-x-px text-base h-10 mt-8">
                <li>
                    <div @click="setPage(1)"
                        class="cursor-pointer flex items-center justify-center px-4 h-10 ms-0 leading-tight text-gray-500 bg-white border border-e-0 border-gray-300 rounded-s-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                        << </div>
                </li>
                <li v-if="response.data.current_page > 2">
                    <div @click="setPage(response.data.current_page - 2)"
                        class="cursor-pointer flex items-center justify-center px-4 h-10 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                        {{
                            response.data.current_page - 2 }}</div>
                </li>
                <li v-if="response.data.current_page > 1">
                    <div @click="setPage(response.data.current_page - 1)"
                        class="cursor-pointer flex items-center justify-center px-4 h-10 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                        {{
                            response.data.current_page - 1 }}</div>
                </li>
                <li>
                    <div aria-current="page"
                        class="cursor-pointer flex items-center justify-center px-4 h-10 text-blue-600 border border-gray-300 bg-blue-50 hover:bg-blue-100 hover:text-blue-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white">
                        {{
                            response.data.current_page }}</div>
                </li>
                <li v-if="response.data.current_page < response.data.last_page">
                    <div @click="setPage(response.data.current_page + 1)"
                        class="cursor-pointer flex items-center justify-center px-4 h-10 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                        {{
                            response.data.current_page + 1 }}</div>
                </li>
                <li v-if="response.data.current_page < response.data.last_page - 1">
                    <div @click="setPage(response.data.current_page + 2)"
                        class="cursor-pointer flex items-center justify-center px-4 h-10 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                        {{
                            response.data.current_page + 2 }}</div>
                </li>
                <li>
                    <div @click="setPage(response.data.last_page)"
                        class="cursor-pointer flex items-center justify-center px-4 h-10 leading-tight text-gray-500 bg-white border border-gray-300 rounded-e-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                        >></div>
                </li>
            </ul>

            <div class="mt-8">
                Всего: {{ response.data.total }}
            </div>
            <div class="inline-flex mt-8">
                <label for="perpage" class="block me-3 my-auto text-sm font-medium text-gray-900 dark:text-white">Машин на
                    странице </label>

                <select id="perpage" v-model="rowPerPage"
                    class="bg-gray-50 border  h-10 border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option value="10"> 10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                    <option value="100">100</option>

                </select>
            </div>
        </div>











        <table class="w-full text-left table-auto min-w-max">
            <thead>
                <tr class="odd:bg-gray-100">
                    <th class="p-4 border-b border-blue-gray-100 bg-blue-gray-50">
                        <p
                            class="block font-sans text-sm antialiased font-normal leading-none text-blue-gray-900 opacity-70">
                            Марка
                        </p>
                        <select @change="selectBrand" v-model="filter_brand"
                            class="bg-gray-50 border w-full mt-5 h-10 border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option value="0">Все</option>
                            <option v-for="(brand) in brands" :key="brand" :value="brand.id"> {{ brand.title }}</option>
                        </select>

                    </th>
                    <th class="p-4 border-b border-blue-gray-100 bg-blue-gray-50">
                        <p
                            class="block font-sans text-sm antialiased font-normal leading-none text-blue-gray-900 opacity-70">
                            Модель
                        </p>
                        <select v-model="filter_model"
                            class="bg-gray-50 border w-full mt-5 h-10 border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option value="0">Все</option>
                            <option v-for="(model) in models_filtered" :key="model" :value="model.id"> {{ model.title }}
                            </option>
                        </select>

                    </th>
                    <th class="p-4 border-b border-blue-gray-100 bg-blue-gray-50">
                        <p
                            class="block font-sans text-sm antialiased font-normal leading-none text-blue-gray-900 opacity-70">
                            Год выпуска
                        </p>
                        <div
                            class="block mt-3 font-sans text-sm antialiased font-normal leading-none text-blue-gray-900 opacity-70">
                            {{ filter_year[0] }} - {{ filter_year[1] }} </div>
                        <Slider :min="limits.min_year" :max="limits.max_year" v-model="filter_year" range
                            class="w-full  mt-3" />

                    </th>
                    <th class="p-4 border-b border-blue-gray-100 bg-blue-gray-50">
                        <p
                            class="block font-sans text-sm antialiased font-normal leading-none text-blue-gray-900 opacity-70">
                            Цена
                        </p>

                        <div
                            class="block mt-3 font-sans text-sm antialiased font-normal leading-none text-blue-gray-900 opacity-70">
                            {{ filter_price[0] }} - {{ filter_price[1] }} </div>
                        <Slider :min="limits.min_price" :max="limits.max_price" v-model="filter_price" range
                            class="w-full  mt-3" />
                    </th>
                    <th class="p-4 border-b border-blue-gray-100 bg-blue-gray-50">
                        <p
                            class="block font-sans text-sm antialiased font-normal leading-none text-blue-gray-900 opacity-70">
                            Тип двигателя
                        </p>
                        <select v-model="filter_engine"
                            class="bg-gray-50 border w-full mt-5 h-10 border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option value="0">Все</option>
                            <option v-for="(engine) in engines" :key="engine" :value="engine.id"> {{ engine.title }}
                            </option>
                        </select>
                    </th>

                    <th class="p-4 border-b border-blue-gray-100 bg-blue-gray-50">
                        <p
                            class="block font-sans text-sm antialiased font-normal leading-none text-blue-gray-900 opacity-70">
                            Тип привода
                        </p>
                        <select v-model="filter_drive"
                            class="bg-gray-50 border w-full mt-5 h-10 border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option value="0">Все</option>
                            <option v-for="(drive) in drives" :key="drive" :value="drive.id"> {{ drive.title }}</option>
                        </select>
                    </th>
                    <th class="p-4 border-b border-blue-gray-100 bg-blue-gray-50 max-w-24">
                        <p
                            class="block font-sans text-sm antialiased font-normal leading-none text-blue-gray-900 opacity-70">
                            Изображения
                        </p>
                    </th>
                </tr>
            </thead>
            <tbody>

                <tr v-if="response" v-for="(line) in response.data.data" :key="line" class="odd:bg-gray-100">
                    <td class="p-4 border-b border-blue-gray-50">
                        <p class="block font-sans text-sm antialiased font-normal leading-normal text-blue-gray-900">
                            {{ line.car_model.brand.title }}

                        </p>
                    </td>
                    <td class="p-4 border-b border-blue-gray-50">
                        <p class="block font-sans text-sm antialiased font-normal leading-normal text-blue-gray-900">
                            {{ line.car_model.title }}
                        </p>
                    </td>
                    <td class="p-4 border-b border-blue-gray-50">
                        <p class="block font-sans text-sm antialiased font-normal leading-normal text-blue-gray-900">
                            {{ new Date(line.manufactured_year).getFullYear() }}
                        </p>
                    </td>
                    <td class="p-4 border-b border-blue-gray-50">
                        <p class="block font-sans text-sm antialiased font-normal leading-normal text-blue-gray-900">
                            {{ line.price }}
                        </p>
                    </td>
                    <td class="p-4 border-b border-blue-gray-50">
                        <p class="block font-sans text-sm antialiased font-medium leading-normal text-blue-gray-900">
                            {{ line.engine_type.title }}
                        </p>
                    </td>
                    <td class="p-4 border-b border-blue-gray-50">
                        <p class="block font-sans text-sm antialiased font-medium leading-normal text-blue-gray-900">
                            {{ line.drive_type.title }}
                        </p>

                    </td>

                    <td class="p-4 border-b border-blue-gray-50 max-w-24">
                        <Carousel v-bind="config">
                            <Slide v-for="(image) in line.images" :key="image">
                                <div @click="image_path = image.path; modal.show()">
                                    <img class="h-auto max-w-20" :src="image.path">
                                </div>
                            </Slide>

                            <template #addons>
                                <Pagination />

                            </template>
                        </Carousel>
                    </td>
                </tr>


            </tbody>
        </table>


        <div v-if="response" class="inline-flex w-full justify-between">
            <ul class="inline-flex -space-x-px text-base h-10 mt-8">
                <li>
                    <div @click="setPage(1)"
                        class="cursor-pointer flex items-center justify-center px-4 h-10 ms-0 leading-tight text-gray-500 bg-white border border-e-0 border-gray-300 rounded-s-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                        << </div>
                </li>
                <li v-if="response.data.current_page > 2">
                    <div @click="setPage(response.data.current_page - 2)"
                        class="cursor-pointer flex items-center justify-center px-4 h-10 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                        {{
                            response.data.current_page - 2 }}</div>
                </li>
                <li v-if="response.data.current_page > 1">
                    <div @click="setPage(response.data.current_page - 1)"
                        class="cursor-pointer flex items-center justify-center px-4 h-10 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                        {{
                            response.data.current_page - 1 }}</div>
                </li>
                <li>
                    <div aria-current="page"
                        class="cursor-pointer flex items-center justify-center px-4 h-10 text-blue-600 border border-gray-300 bg-blue-50 hover:bg-blue-100 hover:text-blue-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white">
                        {{
                            response.data.current_page }}</div>
                </li>
                <li v-if="response.data.current_page < response.data.last_page">
                    <div @click="setPage(response.data.current_page + 1)"
                        class="cursor-pointer flex items-center justify-center px-4 h-10 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                        {{
                            response.data.current_page + 1 }}</div>
                </li>
                <li v-if="response.data.current_page < response.data.last_page - 1">
                    <div @click="setPage(response.data.current_page + 2)"
                        class="cursor-pointer flex items-center justify-center px-4 h-10 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                        {{
                            response.data.current_page + 2 }}</div>
                </li>
                <li>
                    <div @click="setPage(response.data.last_page)"
                        class="cursor-pointer flex items-center justify-center px-4 h-10 leading-tight text-gray-500 bg-white border border-gray-300 rounded-e-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                        >></div>
                </li>
            </ul>

            <div class="mt-8">
                Всего: {{ response.data.total }}
            </div>
            <div class="inline-flex mt-8">
                <label for="perpage" class="block me-3 my-auto text-sm font-medium text-gray-900 dark:text-white">Машин на
                    странице </label>

                <select id="perpage" v-model="rowPerPage"
                    class="bg-gray-50 border  h-10 border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option value="10"> 10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                    <option value="100">100</option>

                </select>
            </div>
        </div>
    </div>







    <div id="default-modal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 max-w-2xl max-h-full">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <img @click="modal.hide()" class="h-auto max-w-xl rounded-lg shadow-xl dark:shadow-gray-800"
                    :src="image_path" alt="image description">
            </div>
        </div>
    </div>
</template>