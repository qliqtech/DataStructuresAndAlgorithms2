using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace DataStructuresAndAlgorithms2
{
    public class Sorting
    {
        //{19,20,10,14,8}
        //int get position of lowest number in array
        //swap position

        public void selectionsort(int[] A, int n)
        {

            for (int i = 0; i < n - 1; i++)
            {

                int position = i;

                for (int j = i + 1; j < n; j++)
                {
                    if (A[j] < A[position])
                        position = j;
                }


                int temp = A[i];

                A[i] = A[position];

                A[position] = temp;

            }


        }


        public void display(int[] A, int n)
        {

            for (int i = 0; i < n; i++)
                Console.WriteLine(A[i] + " ");
            Console.WriteLine();

        }


        // go through loop
        //get position of lowest item and swap


        //code for insertsort
        public int[] insertionsort(int[] A, int n)
        {

            for (int i = 0; i < n; i++)
            {

                int temp = A[i];
                int position = i;

                while (position > 0 && A[position - 1] > temp)
                {
                    A[position] = A[position - 1];
                    position--;
                }
                A[position] = temp;
            }


            return A;
        }


        public void bubblesort(int[] A, int n)
        {

            //for number of passes around
            for (int pass = n - 1; pass >= 0; pass--)
            {

                for (int i = 0; i < n - 1; i++)
                {

                    if (A[i] > A[i + 1])
                    {

                        int temp = A[i];
                        A[i] = A[i + 1];
                        A[i + 1] = temp;
                    }



                }

            }


        }


        public void shellsort(int[] A, int n)
        {

            int gap = n / 2;

            while (gap > 0)
            {
                int i = gap;
                while (i < n)
                {

                    int temp = A[i];
                    int j = i - gap;
                    while (j >= 0 && A[j] > temp)
                    {
                        A[j + gap] = A[j];
                        j = j - gap;
                    }

                    A[j + gap] = temp;
                    i = i + 1;

                   
                }
                gap = gap / 2;


            }

        }

    }
     
}
