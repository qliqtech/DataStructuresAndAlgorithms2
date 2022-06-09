using System;
using System.Collections.Generic;
using System.Text;

public class EvaluateString
{
    public static double Calc(string expression)
    {
        char[] itemsinexpressionstring = expression.ToCharArray();

        // Stack for numbers: 'values'
        Stack<int> values = new Stack<int>();

        // Stack for Operators: 'ops'
        Stack<char> operations = new Stack<char>();

        for (int i = 0; i < itemsinexpressionstring.Length; i++)
        {
            // Current token is a whitespace, skip it
            if (itemsinexpressionstring[i] == ' ')
            {
                continue;
            }

            // Current token is a number,
            // push it to stack for numbers
            if (itemsinexpressionstring[i] >= '0' && itemsinexpressionstring[i] <= '9')
            {
                StringBuilder sbuf = new StringBuilder();

                // There may be more than
                // one digits in number
                while (i < itemsinexpressionstring.Length &&
                        itemsinexpressionstring[i] >= '0' &&
                            itemsinexpressionstring[i] <= '9')
                {
                    sbuf.Append(itemsinexpressionstring[i++]);
                }
                values.Push(int.Parse(sbuf.ToString()));

               
                i--;
            }

            
            else if (itemsinexpressionstring[i] == '(')
            {
                operations.Push(itemsinexpressionstring[i]);
            }

            // Closing brace encountered,
            // solve entire brace
            else if (itemsinexpressionstring[i] == ')')
            {
                while (operations.Peek() != '(')
                {
                    values.Push(applyoperation(operations.Pop(),
                                     values.Pop(),
                                    values.Pop()));
                }
                operations.Pop();
            }

            // Current token is an operator.
            else if (itemsinexpressionstring[i] == '+' ||
                     itemsinexpressionstring[i] == '-' ||
                     itemsinexpressionstring[i] == '*' ||
                     itemsinexpressionstring[i] == '/')
            {

                // While top of 'ops' has same
                // or greater precedence to current
                // token, which is an operator.
                // Apply operator on top of 'ops'
                // to top two elements in values stack
                while (operations.Count > 0 && ismoreimpartantthan(itemsinexpressionstring[i],operations.Peek()))
                {
                    values.Push(applyoperation(operations.Pop(),
                                     values.Pop(),
                                   values.Pop()));
                }

                // Push current token to 'ops'.
                operations.Push(itemsinexpressionstring[i]);
            }
        }

        // Entire expression has been
        // parsed at this point, apply remaining
        // ops to remaining values
        while (operations.Count > 0)
        {
            values.Push(applyoperation(operations.Pop(),
                             values.Pop(),
                            values.Pop()));
        }

        // Top of 'values' contains
        // result, return it
        return values.Pop();
    }

    // Returns true if 'op2' has
    // higher or same precedence as 'op1',
    // otherwise returns false.
    public static bool ismoreimpartantthan(char op1,
                                     char op2)
    {
        if (op2 == '(' || op2 == ')')
        {
            return false;
        }
        if ((op1 == '*' || op1 == '/') &&
               (op2 == '+' || op2 == '-'))
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    // A utility method to apply an
    // operator 'op' on operands 'a' 
    // and 'b'. Return the result.
    public static int applyoperation(char op,
                            int b, int a)
    {
        switch (op)
        {
            case '+':
                return a + b;
            case '-':
                return a - b;
            case '*':
                return a * b;
            case '/':
                if (b == 0)
                {
                    throw new
                    System.NotSupportedException(
                           "Cannot divide by zero");
                }
                return a / b;
        }
        return 0;
    }

  
    
    // Driver method to test above methods
    public static void Main111(string[] args)
    {
        Console.WriteLine(EvaluateString.
                     Calc("10 + 2 * 6"));
        Console.WriteLine(EvaluateString.
                     Calc("100 * 2 + 12"));
        Console.WriteLine(EvaluateString.
                   Calc("100 * ( 2 + 12 )"));
        Console.WriteLine(EvaluateString.
               Calc("100 * ( 2 + 12 ) / 14"));
    }
}
