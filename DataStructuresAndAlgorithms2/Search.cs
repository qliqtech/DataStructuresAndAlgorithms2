using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace DataStructuresAndAlgorithms2
{
    public class Search
    {


        //get array
        //loop through array and stop when arraykey == itemtosearch

        public int linearsearch(int[] array, int itemtosearch) {


            int index = 0;

            foreach (int item in array) {



                if (item == itemtosearch) {


                    return index;
                }

                index++;

            }


            return -1;
        
        }


        //sort array (it is first assumed array is sorted)
        //find index in middle of array
        //if value = key return index
        //get value of index in far left,
        //if value = key return index
        //get value of index in far right,
        //if value = key return index


        public int binarysearch(int numberofcharacters,int[] array, int key) {

            int indexoffirstcharacter = 0;

            int indexoflastcharacter = numberofcharacters - 1;

            int indexofmiddlecharacter = indexoffirstcharacter + indexoflastcharacter;


            if (array[indexoffirstcharacter] == key) {

                return indexoffirstcharacter;
            }

            if (array[indexoflastcharacter] == key)
            {

                return indexoflastcharacter;
            }

            if (array[indexofmiddlecharacter] == key)
            {

                return indexofmiddlecharacter;
            }

            //perform search from left to right


            //get items between 0 and the middle

            int counterleft = 0;

            while (counterleft < indexofmiddlecharacter) {

                counterleft++;

                if (array[counterleft] == key)
                {


                    return counterleft;
                }

            }


            int counterright = indexoflastcharacter;

            while (counterright > indexofmiddlecharacter)
            {

                counterright++;

                if (array[counterright] == key)
                {


                    return counterright;
                }

            }

            return -1;
        }


    }
}
