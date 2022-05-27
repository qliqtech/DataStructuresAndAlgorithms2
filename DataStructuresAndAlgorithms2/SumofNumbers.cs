using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace DataStructuresAndAlgorithms2
{
    public class SumofNumbers
    {

        public int sumn(int n)
        {


            return n * (n + 1) / 2;
        }


        public int sumniteration(int n)
        {

            int total = 0;
            int i = 1;

            while (i <= n)
            {
                total = total + i;

                i = i + 1;

            }

            return total;

        }


        public int sumnrecursion(int n) { 
        
            if(n == 0)
                return 0;
            return sumnrecursion(n - 1) + n;
        
        
        }





        public int sumniteration1(int n)
        {

            //start from 0 to 5
            //keep adding numbers from 0 to 5

            int sum = 0;

            for (int i = 1; i <= n; i++) {

                sum = sum + i;

            }
          

            return sum;

        }
































    }
}
